<?php

namespace App\Http\Controllers;

use App\Models\MarketPlace;
use App\Models\MarketGallery;
use App\Models\MarketService;
use App\Models\MarketSubscription;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Services\MailService;
use App\Mail\MarketplaceWelcomeMail;
use App\Mail\MarketplacePlanMail;

class MarketPlaceController extends Controller
{
    /**
     * Strict AJAX-JSON detection for grid endpoints.
     * Prevents raw JSON pretty-print showing as a page on direct visits:
     * direct browser navigation sends Accept: text/html (wantsJson=false),
     * while our fetch() sends Accept: application/json + X-Requested-With.
     */
    protected function wantsGridJson(Request $request): bool
    {
        return $request->ajax() && $request->wantsJson();
    }
    /**
     * Display the marketplace listing.
     */
    public function index(Request $request)
    {
        $pageTitle = "Talent Marketplace";
        $type = $request->query('type');
        $location = $request->query('location');
        $search = $request->query('search');

        $featuredQuery = MarketPlace::query()
            ->with(['subscriptions.plan'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('status', 1)
            ->where(function($q) {
                $q->where('is_featured', true)
                  ->orWhereHas('subscriptions', function($sub) {
                      $sub->where(function($sq) {
                          $sq->where('end_date', '>', now())->orWhereNull('end_date');
                      })->whereHas('plan', function($pq) {
                          $pq->where('is_featured_plan', true);
                      });
                  });
            });

        $regularQuery = MarketPlace::query()
            ->with(['subscriptions.plan'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('status', 1)->where('is_featured', false);
        
        if ($type && $type != 'All Roles') {
            $featuredQuery->where('type', $type);
            $regularQuery->where('type', $type);
        }

        if ($location && $location != 'All Locations') {
            $featuredQuery->where('location', $location);
            $regularQuery->where('location', $location);
        }

        if ($search) {
            $searchFilter = function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('type', 'LIKE', "%$search%")
                  ->orWhere('location', 'LIKE', "%$search%")
                  ->orWhere('skills', 'LIKE', "%$search%")
                  ->orWhere('business_name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            };
            $featuredQuery->where($searchFilter);
            $regularQuery->where($searchFilter);
        }
        
        $featuredCreators = $featuredQuery->inRandomOrder()->take(8)->get();
        $allCreators = $regularQuery->inRandomOrder()->take(24)->get();

        $talent = null;
        $activeSubscription = null;
        if (Session::has('marketplace_user_id')) {
            $talent = MarketPlace::find(Session::get('marketplace_user_id'));
            if ($talent) {
                $activeSubscription = $talent->subscriptions()->where(function($q) {
                    $q->where('end_date', '>', now())->orWhereNull('end_date');
                })->latest()->first();
            }
        }

        if ($request->ajax() && $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data'   => $allCreators,
                'featured' => $featuredCreators
            ]);
        }

        // Latest profiles only, one random gallery image each (no repeated profiles)
        $latestProfileIds = MarketPlace::where('status', 1)
            ->whereHas('galleries')
            ->latest()
            ->take(12)
            ->pluck('id');

        $galleryItems = MarketGallery::with('marketplace')
            ->whereIn('marketplace_id', $latestProfileIds)
            ->inRandomOrder()
            ->get()
            ->unique('marketplace_id')
            ->take(8)
            ->values();

        $stats = [
            'profiles'     => MarketPlace::active()->count(),
            'featured'     => MarketPlace::active()->where('is_featured', true)->count(),
            'services'     => \App\Models\MarketService::count()
        ];

        $plans = UserPlan::all();

        return view('frontend.marketplace.index', compact('pageTitle', 'allCreators', 'featuredCreators', 'talent', 'activeSubscription', 'galleryItems', 'stats', 'plans'));
    }

    /**
     * Display all profiles with pagination.
     */
    public function all(Request $request)
    {
        $pageTitle = "All Profiles";
        $type = $request->query('type');
        $search = $request->query('search');
        $filter = $request->query('filter');
        
        $query = MarketPlace::query()
            ->with(['subscriptions.plan'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('status', 1);
        
        if ($type) {
            $query->where('type', $type);
        }

        if ($filter == 'featured') {
            $query->where(function($q) {
                $q->where('is_featured', true)
                  ->orWhereHas('subscriptions', function($sub) {
                      $sub->where(function($sq) {
                          $sq->where('end_date', '>', now())->orWhereNull('end_date');
                      })->whereHas('plan', function($pq) {
                          $pq->where('is_featured_plan', true);
                      });
                  });
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('type', 'LIKE', "%$search%")
                  ->orWhere('location', 'LIKE', "%$search%")
                  ->orWhere('skills', 'LIKE', "%$search%")
                  ->orWhere('business_name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }
        
        if ($filter == 'latest') {
            $query->latest();
        } else {
            // Default: show featured first, then newest
            $query->orderByRaw('(SELECT COUNT(*) FROM market_subscriptions WHERE marketplace_id = market_places.id AND (end_date > NOW() OR end_date IS NULL)) DESC')
                  ->latest();
        }

        $marketplaces = $query->paginate(24)->appends($request->query());

        $talent = null;
        if (Session::has('marketplace_user_id')) {
            $talent = MarketPlace::find(Session::get('marketplace_user_id'));
        }

        if ($this->wantsGridJson($request)) {
            return response()->json([
                'html' => view('frontend.marketplace.partials.profiles_grid', compact('marketplaces'))->render(),
                'pagination' => (string) $marketplaces->links()
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return view('frontend.marketplace.all', compact('pageTitle', 'marketplaces', 'talent'));
    }

    /**
     * Display featured creators only.
     */
    public function featured(Request $request)
    {
        $pageTitle = "Featured Profiles";
        $type = $request->query('type');
        $location = $request->query('location');
        $search = $request->query('search');

        $featuredCreators = MarketPlace::query()
            ->with(['subscriptions.plan'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('status', 1)
            ->where(function($q) {
                $q->where('is_featured', true)
                  ->orWhereHas('subscriptions', function($sub) {
                      $sub->where(function($sq) {
                          $sq->where('end_date', '>', now())->orWhereNull('end_date');
                      })->whereHas('plan', function($pq) {
                          $pq->where('is_featured_plan', true);
                      });
                  });
            });

        if ($type && $type != 'All Roles') {
            $featuredCreators->where('type', $type);
        }

        if ($location && $location != 'All Locations') {
            $featuredCreators->where('location', $location);
        }

        if ($search) {
            $featuredCreators->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('type', 'LIKE', "%$search%")
                  ->orWhere('location', 'LIKE', "%$search%")
                  ->orWhere('skills', 'LIKE', "%$search%")
                  ->orWhere('business_name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $featuredCreators = $featuredCreators->latest()->paginate(15)->appends($request->query());

        $talent = null;
        if (Session::has('marketplace_user_id')) {
            $talent = MarketPlace::find(Session::get('marketplace_user_id'));
        }

        if ($this->wantsGridJson($request)) {
            return response()->json([
                'html' => view('frontend.marketplace.partials.profiles_grid', ['marketplaces' => $featuredCreators])->render(),
                'pagination' => (string) $featuredCreators->links()
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return view('frontend.marketplace.featured', compact('pageTitle', 'featuredCreators', 'talent'));
    }

    /**
     * Display an individual portfolio.
     */
    public function portfolio($slug)
    {
        $marketplace = MarketPlace::with(['galleries', 'services', 'portfolios.images'])
                                  ->where(function($q) use ($slug) {
                                      $q->where('slug', $slug)->orWhere('id', $slug);
                                  })
                                  ->firstOrFail();
        
        $pageTitle = $marketplace->name . " Portfolio";
        
        $viewerUserId = auth()->id();
        $viewerMarketplaceId = session('marketplace_user_id');
        $messages = collect();
        if ($viewerUserId || $viewerMarketplaceId) {
            // Fetch permissively and filter in PHP to avoid SQL grouping bugs with OR precedence
            $candidates = \App\Models\MarketMessage::where(function($q) use ($marketplace, $viewerMarketplaceId, $viewerUserId) {
                $q->where('marketplace_id', $marketplace->id)
                  ->orWhere('sender_marketplace_id', $marketplace->id);
                if ($viewerMarketplaceId) {
                    $q->orWhere('marketplace_id', $viewerMarketplaceId)
                      ->orWhere('sender_marketplace_id', $viewerMarketplaceId);
                }
                if ($viewerUserId) {
                    $q->orWhere('user_id', $viewerUserId);
                }
            })->orderBy('created_at', 'asc')->get();

            $messages = $candidates->filter(function($msg) use ($marketplace, $viewerUserId, $viewerMarketplaceId) {
                $isViewerUser = $viewerUserId && (int)$msg->user_id === (int)$viewerUserId;
                $isViewerMarketplaceSender = $viewerMarketplaceId && (int)$msg->sender_marketplace_id === (int)$viewerMarketplaceId;
                $isPortfolioInvolved = (int)$msg->marketplace_id === (int)$marketplace->id || (int)$msg->sender_marketplace_id === (int)$marketplace->id;
                $isViewerInvolved = $isViewerUser || $isViewerMarketplaceSender || ((int)$msg->marketplace_id === (int)$viewerMarketplaceId);
                // Keep only messages where both portfolio and viewer are involved (2-party conversation)
                return $isPortfolioInvolved && $isViewerInvolved;
            })->values();
        }

        return view('frontend.marketplace.portfolio', compact('pageTitle', 'marketplace', 'messages'));
    }

    /**
     * Store a contact inquiry for a marketplace member.
     */
    public function storeContact(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $marketplace = MarketPlace::findOrFail($id);
        $marketplace->contacts()->create($request->all());
        $notify[] = ['success', 'Your message has been sent.'];
        return back()->withNotify($notify);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $isMarketplaceSender = session()->has('marketplace_user_id');
        $senderId = $isMarketplaceSender ? session()->get('marketplace_user_id') : auth()->id();

        if (!$senderId) {
            abort(403, 'Unauthorized.');
        }

        $msg = \App\Models\MarketMessage::create([
            'marketplace_id' => $id,
            'user_id' => $isMarketplaceSender ? null : $senderId,
            'sender_marketplace_id' => $isMarketplaceSender ? $senderId : null,
            'sender_type' => $isMarketplaceSender ? 'marketplace' : 'user',
            'message' => $request->message,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $msg,
            ]);
        }

        $notify[] = ['success', 'Message sent!'];
        return back()->withNotify($notify);
    }

    public function replyMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|string', // This is now chat_id (e.g. u_5 or m_2)
        ]);

        $marketplaceId = \Illuminate\Support\Facades\Session::get('marketplace_user_id');
        
        $isMarketplaceReceiver = str_starts_with($request->user_id, 'm_');
        $receiverId = substr($request->user_id, 2);

        $msg = \App\Models\MarketMessage::create([
            'marketplace_id' => $isMarketplaceReceiver ? $receiverId : $marketplaceId,
            'user_id' => $isMarketplaceReceiver ? null : $receiverId,
            'sender_marketplace_id' => $marketplaceId,
            'sender_type' => 'marketplace',
            'message' => $request->message,
        ]);

        $marketplace = \App\Models\MarketPlace::find($marketplaceId);
        
        if ($isMarketplaceReceiver) {
            $receiverMarketplace = \App\Models\MarketPlace::find($receiverId);
            // Can send email to marketplace user if needed, but not strictly required
        } else {
            $user = \App\Models\User::find($receiverId);
            if ($user && $marketplace) {
                notify($user, 'DEFAULT', [
                    'subject' => 'New Reply from ' . $marketplace->name,
                    'message' => 'You have received a new message from ' . $marketplace->name . ' on your Binteo Profile. Log in to view and reply.',
                ], ['email', 'sms', 'push'], false);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $msg,
            ]);
        }

        $notify[] = ['success', 'Reply sent!'];
        return back()->withNotify($notify);
    }

    public function updateMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        $marketplaceId = \Illuminate\Support\Facades\Session::get('marketplace_user_id');
        $msg = \App\Models\MarketMessage::find($id);

        if ($msg && $marketplaceId && ((int)$msg->marketplace_id === (int)$marketplaceId || (int)$msg->sender_marketplace_id === (int)$marketplaceId)) {
            $msg->message = $request->message;
            $msg->save();

            if ($request->expectsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Message updated successfully', 'data' => $msg]);
            }
            $notify[] = ['success', 'Message updated successfully'];
            return back()->withNotify($notify);
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Message not found or unauthorized.'], 404);
        }
        abort(404);
    }

    public function deleteMessage(Request $request, $id)
    {
        $marketplaceId = \Illuminate\Support\Facades\Session::get('marketplace_user_id');
        $msg = \App\Models\MarketMessage::find($id);

        if ($msg && $marketplaceId && ((int)$msg->marketplace_id === (int)$marketplaceId || (int)$msg->sender_marketplace_id === (int)$marketplaceId)) {
            $msg->delete();

            if ($request->expectsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Message deleted successfully']);
            }
            $notify[] = ['success', 'Message deleted successfully'];
            return back()->withNotify($notify);
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Message not found or unauthorized.'], 404);
        }
        abort(404);
    }

    public function userUpdateMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        
        $isMarketplaceSender = session()->has('marketplace_user_id');
        $senderId = $isMarketplaceSender ? session()->get('marketplace_user_id') : auth()->id();
        
        if (!$senderId) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

        $msg = \App\Models\MarketMessage::find($id);
        if ($msg) {
            $isAuthorized = false;
            if ($isMarketplaceSender && ((int)$msg->sender_marketplace_id === (int)$senderId || (int)$msg->marketplace_id === (int)$senderId)) {
                $isAuthorized = true;
            } elseif (!$isMarketplaceSender && (int)$msg->user_id === (int)$senderId) {
                $isAuthorized = true;
            }

            if ($isAuthorized) {
                $msg->message = $request->message;
                $msg->save();

                if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'message' => 'Message updated successfully', 'data' => $msg]);
                }
                $notify[] = ['success', 'Message updated successfully'];
                return back()->withNotify($notify);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Message not found or unauthorized.'], 404);
        }
        abort(404);
    }

    public function userDeleteMessage(Request $request, $id)
    {
        $isMarketplaceSender = session()->has('marketplace_user_id');
        $senderId = $isMarketplaceSender ? session()->get('marketplace_user_id') : auth()->id();
        
        if (!$senderId) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

        $msg = \App\Models\MarketMessage::find($id);
        if ($msg) {
            $isAuthorized = false;
            if ($isMarketplaceSender && ((int)$msg->sender_marketplace_id === (int)$senderId || (int)$msg->marketplace_id === (int)$senderId)) {
                $isAuthorized = true;
            } elseif (!$isMarketplaceSender && (int)$msg->user_id === (int)$senderId) {
                $isAuthorized = true;
            }

            if ($isAuthorized) {
                $msg->delete();

                if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'message' => 'Message deleted successfully']);
                }
                $notify[] = ['success', 'Message deleted successfully'];
                return back()->withNotify($notify);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Message not found or unauthorized.'], 404);
        }
        abort(404);
    }

    /**
     * Marketplace Authentication
     */
    public function showRegistrationForm() 
    { 
        $pageTitle = "Talent Registration";
        $general = gs();
        return view('frontend.marketplace.register', compact('pageTitle', 'general')); 
    }

    public function showLoginForm() { return view('frontend.marketplace.login'); }

    public function register(Request $request)
    {
        Log::info('Marketplace Registration Attempt:', $request->except(['password', 'password_confirmation']));

        try {
            $request->validate([
                'name'     => ['required', 'string', 'min:2', 'max:60', 'regex:/^(?=.*[a-zA-Z])[a-zA-Z\s\.\'\-]+$/'],
                'email'    => [
                    'required', 'string', 'email:rfc,dns', 'max:255', 'unique:market_places',
                    function ($attribute, $value, $fail) {
                        $blockedDomains = ['gmasil.com', 'gsail.com', 'gmial.com', 'gmai.com', 'yaho.com', 'hotmial.com'];
                        $domain = strtolower(explode('@', $value)[1] ?? '');
                        if (in_array($domain, $blockedDomains)) {
                            $fail('The '.$attribute.' domain is invalid or incorrectly spelled.');
                        }
                    }
                ],
                'number'   => ['required', 'string', 'max:20', 'unique:market_places'],
                'password' => ['required', 'string', 'min:8', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                'location' => ['required', 'string', 'min:2', 'max:255', 'regex:/^(?=.*[a-zA-Z])[a-zA-Z\s\.,\'\-]+$/'],
                'type'     => ['required', 'string', 'max:255'],
                'years_of_experience' => ['required', 'string', 'max:30'],
                'bio'      => ['required', 'string'],
                'skills'   => ['nullable', 'string'],
                'portfolio_url' => ['nullable', 'string'],
                'website_url' => ['nullable', 'string'],
                'business_name' => ['nullable', 'string'],
                'business_type' => ['nullable', 'string'],
                'other_business_type' => ['nullable', 'string'],
                'agree_terms' => ['required', 'accepted'],
                'agree_creator_terms' => ['required', 'accepted'],
            ]);

            Log::info('Validation passed for marketplace registration.');

            $finalBusinessType = $request->business_type === 'other' ? $request->other_business_type : $request->business_type;

            $user = MarketPlace::create([
                'name' => $request->name,
                'email' => $request->email,
                'number' => $request->number,
                'password' => Hash::make($request->password),
                'location' => $request->location,
                'type' => $request->type,
                'years_of_experience' => $request->years_of_experience,
                'more_info' => $request->bio,
                'skills' => $request->skills,
                'portfolio_url' => $request->portfolio_url,
                'website_url' => $request->website_url,
                'business_name' => $request->business_name,
                'business_type' => $finalBusinessType,
                'status' => 1
            ]);

            Log::info('Marketplace user created successfully in DB.', ['user_id' => $user->id]);

            Session::put('marketplace_user_id', $user->id);

            try {
                app(MailService::class)->sendMailable($user->email, new MarketplaceWelcomeMail($user));
                Log::info('Marketplace Welcome Email sent.', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Marketplace Welcome Email Failed for ' . $user->email . ': ' . $e->getMessage());
            }

            $notify[] = ['success', 'Account created successfully.'];
            return redirect()->route('marketplace.dashboard')->withNotify($notify);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Marketplace Registration Validation Failed: ', $e->errors());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Marketplace Registration General Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $notify[] = ['error', 'Something went wrong during registration. Please try again later.'];
            return back()->withNotify($notify)->withInput();
        }
    }

    public function checkAvailability(Request $request)
    {
        $field = $request->field;
        $value = $request->value;

        if ($field === 'email') {
            // First check if it's a known typo domain
            $blockedDomains = ['gmasil.com', 'gsail.com', 'gmial.com', 'gmai.com', 'yaho.com', 'hotmial.com'];
            $domain = strtolower(explode('@', $value)[1] ?? '');
            
            if (in_array($domain, $blockedDomains)) {
                return response()->json(['error' => 'Invalid email domain format. Please check for spelling mistakes.']);
            }

            // Then check DNS records
            $validator = \Illuminate\Support\Facades\Validator::make(
                ['email' => $value],
                ['email' => 'email:rfc,dns']
            );

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid email address or domain does not exist.']);
            }

            if (MarketPlace::where('email', $value)->exists()) {
                return response()->json(['error' => 'This email is already registered in our marketplace.']);
            }
        }

        if ($field === 'number') {
            if (MarketPlace::where('number', $value)->exists()) {
                return response()->json(['error' => 'This phone number is already registered in our marketplace.']);
            }
        }

        return response()->json(['success' => true]);
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        $user = MarketPlace::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $deletionRequest = $user->deletionRequest()->where('status', 'pending')->first();
            if ($deletionRequest) {
                return back()->with([
                    'deletion_pending' => true,
                    'deletion_reason' => $deletionRequest->reason . ($deletionRequest->custom_reason ? ' (' . $deletionRequest->custom_reason . ')' : '')
                ]);
            }

            // Force logout of any normal user session to enforce strict separation
            if (auth()->guard('web')->check()) {
                auth()->guard('web')->logout();
            }

            Session::put('marketplace_user_id', $user->id);
            $notify[] = ['success', 'Signed in successfully. Welcome back!'];
            return redirect()->route('marketplace.dashboard')->withNotify($notify);
        }

        $notify[] = ['error', 'The provided credentials do not match our records.'];
        return back()->withNotify($notify);
    }

    /**
     * Update profile avatar image
     */
    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096']);
        $userId = Session::get('marketplace_user_id');
        $client = MarketPlace::findOrFail($userId);

        // Delete old image if it exists
        if ($client->image) {
            \App\Helpers\ImageHelper::deleteImage($client->image);
        }

        $path = \App\Helpers\ImageHelper::uploadToR2($request->file('avatar'), 'marketplace/avatars');
        $client->update(['image' => $path]);
        $notify[] = ['success', 'Profile photo updated successfully.'];
        return redirect()->route('marketplace.dashboard')->withNotify($notify);

    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'location' => 'nullable|string|max:255',
            'more_info' => 'nullable|string',
            'years_of_experience' => 'nullable|string|max:30',
            'skills' => 'nullable|string',
            'business_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
            'other_business_type' => 'nullable|string|max:255',
            'portfolio_url' => 'nullable|url',
            'website_url' => 'nullable|url',
            'facebook_link' => 'nullable|url',
            'instagram_link' => 'nullable|url',
            'twitter_link' => 'nullable|url',
            'projects_count' => 'nullable|numeric|min:0',
            'satisfaction_rate' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $userId = session()->get('marketplace_user_id');
        $client = MarketPlace::findOrFail($userId);
        
        $data = $request->except(['_token', 'other_business_type']);
        if ($request->skills) {
            $data['skills'] = array_map('trim', explode(',', $request->skills));
        }
        
        if ($request->business_type === 'other' && $request->other_business_type) {
            $data['business_type'] = $request->other_business_type;
        }

        if ($request->hasFile('image')) {
            \Illuminate\Support\Facades\Log::info('Marketplace profile image upload started for user: ' . $client->id);
            if ($client->image) {
                \App\Helpers\ImageHelper::deleteImage($client->image);
            }
            $data['image'] = \App\Helpers\ImageHelper::uploadToR2($request->file('image'), 'marketplace/avatars');
            \Illuminate\Support\Facades\Log::info('Marketplace profile image upload successful: ' . $data['image']);
        }

        if ($request->hasFile('cover_image')) {
            \Illuminate\Support\Facades\Log::info('Marketplace cover image upload started for user: ' . $client->id);
            if ($client->cover_image) {
                \App\Helpers\ImageHelper::deleteImage($client->cover_image);
            }
            $data['cover_image'] = \App\Helpers\ImageHelper::uploadToR2($request->file('cover_image'), 'marketplace/covers');
            \Illuminate\Support\Facades\Log::info('Marketplace cover image upload successful: ' . $data['cover_image']);
        }

        $client->update($data);

        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    /**
     * Marketplace Personal Dashboard
     */
    public function markMessagesRead()
    {
        $userId = Session::get('marketplace_user_id');
        if ($userId) {
            \App\Models\MarketMessage::where('marketplace_id', $userId)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        }
        return response()->json(['status' => 'success']);
    }

    public function dashboard()
    {
        $userId = Session::get('marketplace_user_id');
        if (!$userId) return redirect()->route('marketplace.login');

        $client = MarketPlace::with(['galleries', 'services', 'contacts'])->findOrFail($userId);
        $plans = UserPlan::all();
        $subscriptions = MarketSubscription::where('marketplace_id', $userId)->latest()->get();
        $activeSubscription = $client->subscriptions()->where(function($q) {
            $q->where('end_date', '>', now())->orWhereNull('end_date');
        })->latest()->first();
        $transactions = \App\Models\Deposit::with(['plan', 'marketplace'])
            ->where('marketplace_id', $userId)
            ->where(function($q) {
                $q->whereNotNull('plan_id')
                  ->orWhere('detail', 'like', '%marketplace_plan%');
            })
            ->latest()
            ->paginate(10);

        // Backfill plan names for old deposits that have plan_id only in detail JSON
        $missingPlanIds = $transactions->filter(fn($d) => !$d->plan && $d->detail && isset($d->detail->plan_id))
            ->pluck('detail.plan_id')
            ->unique()
            ->toArray();
        if (!empty($missingPlanIds)) {
            $loadedPlans = \App\Models\UserPlan::whereIn('id', $missingPlanIds)->get()->keyBy('id');
            foreach ($transactions as $d) {
                if (!$d->plan && $d->detail && isset($d->detail->plan_id) && $loadedPlans->has($d->detail->plan_id)) {
                    $d->setRelation('plan', $loadedPlans->get($d->detail->plan_id));
                }
            }
        }

        $pageTitle = "Talent Dashboard";

        $messages = \App\Models\MarketMessage::with(['user', 'senderMarketplace'])
            ->where(function($q) use ($client) {
                $q->where('marketplace_id', $client->id)
                  ->orWhere('sender_marketplace_id', $client->id);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(function($msg) use ($client) {
                if ((int)$msg->sender_marketplace_id === (int)$client->id) {
                    if ($msg->user_id) {
                        return 'u_' . $msg->user_id;
                    }
                    return 'm_' . $msg->marketplace_id;
                }
                if ($msg->sender_marketplace_id) {
                    return 'm_' . $msg->sender_marketplace_id;
                }
                if ($msg->user_id) {
                    return 'u_' . $msg->user_id;
                }
                return 'u_' . ($msg->user_id ?? 0);
            });

        return view('frontend.marketplace.dashboard', compact('pageTitle', 'client', 'plans', 'subscriptions', 'activeSubscription', 'transactions', 'messages'));
    }

    public function logout()
    {
        Session::forget('marketplace_user_id');
        $notify[] = ['success', 'Signed out successfully.'];
        return redirect()->route('marketplace.index')->withNotify($notify);
    }

    /**
     * Portfolio Asset Management
     */
    public function portfolioStore(Request $request)
    {
        $request->validate([
            'portfolio_id' => 'nullable|exists:market_portfolios,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $talent = MarketPlace::findOrFail(Session::get('marketplace_user_id'));

        if ($request->filled('portfolio_id')) {
            $portfolio = \App\Models\MarketPortfolio::where('marketplace_id', $talent->id)->findOrFail($request->portfolio_id);
            $portfolio->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->has('status') ? 1 : 0,
            ]);
            $msg = 'Portfolio updated successfully.';
        } else {
            $portfolio = \App\Models\MarketPortfolio::create([
                'marketplace_id' => $talent->id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->has('status') ? 1 : 0,
                'sort_order' => 0,
            ]);
            $msg = 'Portfolio added successfully.';
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $imageUrl = \App\Helpers\ImageHelper::uploadToR2($image, 'marketplace/portfolios');
                    if ($imageUrl) {
                        \App\Models\MarketPortfolioImage::create([
                            'market_portfolio_id' => $portfolio->id,
                            'image' => $imageUrl,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Skip
                }
            }
        }

        $notify[] = ['success', $msg];
        return back()->withNotify($notify);
    }

    public function portfolioDelete($id)
    {
        $talent = MarketPlace::findOrFail(Session::get('marketplace_user_id'));
        $portfolio = \App\Models\MarketPortfolio::where('marketplace_id', $talent->id)->findOrFail($id);
        
        foreach ($portfolio->images as $image) {
            $image->delete();
        }
        
        $portfolio->delete();

        $notify[] = ['success', 'Portfolio deleted successfully.'];
        return back()->withNotify($notify);
    }

    public function portfolioImageDelete($id)
    {
        $talent = MarketPlace::findOrFail(Session::get('marketplace_user_id'));
        $image = \App\Models\MarketPortfolioImage::whereHas('portfolio', function ($q) use ($talent) {
            $q->where('marketplace_id', $talent->id);
        })->findOrFail($id);

        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    public function galleryStore(Request $request)
    {
        $request->validate(['market_gallery' => 'required|image|max:5120']);
        $userId = Session::get('marketplace_user_id');
        $marketplace = MarketPlace::findOrFail($userId);

        if ($request->hasFile('market_gallery')) {
            $path = \App\Helpers\ImageHelper::uploadToR2($request->file('market_gallery'), 'marketplace/gallery');
            $marketplace->galleries()->create(['market_gallery' => $path]);
        }
        $notify[] = ['success', 'Photo added to your gallery.'];
        return back()->withNotify($notify);

    }

    public function serviceStore(Request $request)
    {
        $request->validate([
            'service_img'   => 'required|image|max:2048',
            'service_name'  => 'required|string|max:255',
            'service_brief' => 'nullable|string',
        ]);
        $userId = Session::get('marketplace_user_id');
        $marketplace = MarketPlace::findOrFail($userId);

        if ($request->hasFile('service_img')) {
            $path = \App\Helpers\ImageHelper::uploadToR2($request->file('service_img'), 'marketplace/services');
            $marketplace->services()->create([
                'service_img'   => $path,
                'service_name'  => $request->service_name,
                'service_brief' => $request->service_brief,
            ]);
        }
        $notify[] = ['success', 'Service added successfully.'];
        return back()->withNotify($notify);

    }

    /**
     * Subscription Logic
     */
    public function previewPlan($id)
    {
        $plan = UserPlan::findOrFail($id);
        $pageTitle = "Plan Preview";
        return view('frontend.marketplace.preview', compact('pageTitle', 'plan'));
    }

    public function subscribePlan(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:user_plans,id']);
        $plan = UserPlan::findOrFail($request->plan_id);

        $notify[] = ['info', 'Please complete the payment to activate your plan'];
        return redirect()->route('user.deposit', [
            'amount'  => $plan->plan_price,
            'plan_id' => $plan->id
        ])->withNotify($notify);
    }

    public function buyPlan($id)
    {
        \Log::info('--- MarketPlaceController@buyPlan Execution Started ---');
        $userId = Session::get('marketplace_user_id');
        \Log::info('Attempting to buy Marketplace Plan ID: ' . $id . ' for talent ID: ' . $userId);

        $plan = UserPlan::findOrFail($id);
        $talent = MarketPlace::findOrFail($userId);
        \Log::info('Marketplace Plan details: ', $plan->toArray());

        // Check if talent already has this plan active
        $existing = MarketSubscription::where('marketplace_id', $userId)
            ->where('plan_id', $plan->id)
            ->where('end_date', '>', now())
            ->first();
            
        if ($existing) {
            return response()->json(['error' => 'You already have this plan active.']);
        }

        // Create a deposit record
        $deposit = new \App\Models\Deposit();
        $deposit->marketplace_id = $userId;
        $deposit->plan_id = $plan->id;
        $deposit->method_code = 507; 
        $deposit->method_currency = 'INR';
        $deposit->amount = $plan->plan_price;
        $deposit->charge = 0;
        $deposit->rate = 1;
        $deposit->final_amount = $plan->plan_price;
        $deposit->trx = getTrx();
        $deposit->status = 0;
        $deposit->detail = json_encode(['plan_id' => $plan->id, 'type' => 'marketplace_plan']);
        $deposit->save();

        if ($plan->plan_price <= 0) {
            // Free Plan Logic
            MarketSubscription::create([
                'marketplace_id' => $userId,
                'plan_id' => $plan->id,
                'plan_name' => $plan->plan_name,
                'plan_price' => $plan->plan_price,
                'plan_duration' => $plan->plan_duration,
                'start_date' => now(),
                'end_date' => $plan->plan_duration == 0 ? null : (function() use ($plan) {
                    $d = $plan->plan_duration;
                    $date = now();
                    if ($d == 30 || $d == 1) return $date->addMonth();
                    if ($d == 90 || $d == 3) return $date->addMonths(3);
                    if ($d == 180 || $d == 6) return $date->addMonths(6);
                    if ($d == 365 || $d == 12) return $date->addYear();
                    return $date->addMonths($d);
                })(),
            ]);
            
            if ($plan->is_featured_plan) {
                $talent->is_featured = true;
                $talent->featured_until = $plan->plan_duration == 0 ? null : (function() use ($plan) {
                    $d = $plan->plan_duration;
                    $date = now();
                    if ($d == 30 || $d == 1) return $date->addMonth();
                    if ($d == 90 || $d == 3) return $date->addMonths(3);
                    if ($d == 180 || $d == 6) return $date->addMonths(6);
                    if ($d == 365 || $d == 12) return $date->addYear();
                    return $date->addMonths($d);
                })();
                $talent->save();
            }

            try {
                app(MailService::class)->sendMailable($talent->email, new MarketplacePlanMail($talent, $plan));
            } catch (\Exception $e) {
                Log::error('Marketplace Plan Email Failed for ' . $talent->email . ': ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Free plan activated!', 'redirect' => route('marketplace.dashboard')]);
        }

        // Razorpay Order Creation
        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');

        \Log::channel('razorpay')->info('MARKET | Order creation requested', [
            'user_id'    => auth()->id(),
            'plan_id'    => $plan->id ?? null,
            'amount'     => $deposit->final_amount,
            'trx'        => $deposit->trx,
            'key_prefix' => substr((string) $apiKey, 0, 12),
        ]);

        \Log::info('Razorpay Credentials Check (Marketplace)', [
            'key_exists' => !empty($apiKey),
            'secret_exists' => !empty($apiSecret)
        ]);

        if (!$apiKey || !$apiSecret) {
            \Log::channel('razorpay')->error('MARKET | Razorpay configuration missing');
            \Log::error('Razorpay configuration missing in MarketPlaceController.');
            return response()->json(['error' => 'Razorpay configuration missing. Please check .env file.']);
        }

        try {
            $api = new \Razorpay\Api\Api($apiKey, $apiSecret);
            \Log::info('Attempting to create Razorpay Order for TRX: ' . $deposit->trx);
            $order = $api->order->create([
                'receipt'         => $deposit->trx,
                'amount'          => round($deposit->final_amount * 100),
                'currency'        => 'INR',
                'payment_capture' => '1',
            ]);
            \Log::info('Razorpay Order created successfully. Order ID: ' . $order->id);

            \Log::channel('razorpay')->info('MARKET | Order created successfully on Razorpay', [
                'order_id'   => $order->id,
                'amount'     => $order->amount,
                'status'     => $order->status ?? null,
                'trx'        => $deposit->trx,
            ]);
            
            $deposit->btc_wallet = $order->id;
            $deposit->save();

            return response()->json([
                'success' => true,
                'key' => $apiKey,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'order_id' => $order->id,
                'name' => $talent->name,
                'email' => $talent->email,
                'contact' => $talent->number ?? "",
                'plan_name' => $plan->plan_name,
                'trx' => $deposit->trx
            ]);
        } catch (\Exception $e) {
            \Log::channel('razorpay')->error('MARKET | Order creation FAILED on Razorpay', [
                'trx'   => $deposit->trx ?? null,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function verifyPlanPayment(Request $request)
    {
        \Log::info('--- MarketPlaceController@verifyPlanPayment Execution Started ---');
        \Log::info('Incoming Verify Payload:', $request->all());

        \Log::channel('razorpay')->info('MARKET | Verification callback received (handler fired = payment reached Razorpay success)', [
            'trx'                 => $request->trx,
            'razorpay_order_id'   => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'trx' => 'required'
        ]);

        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');
        $api = new \Razorpay\Api\Api($apiKey, $apiSecret);
        
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            \Log::info('Verifying Signature with attributes:', $attributes);
            $api->utility->verifyPaymentSignature($attributes);
            \Log::info('Signature verification SUCCESS.');

            \Log::channel('razorpay')->info('MARKET | Signature verified', ['trx' => $request->trx]);
            
            // Payment is auto-captured (payment_capture=1), verify it's captured
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            \Log::info('Marketplace Payment Status: ' . $payment->status, ['trx' => $request->trx, 'amount' => $payment->amount]);

            \Log::channel('razorpay')->info('MARKET | Payment fetched from Razorpay API', [
                'trx'        => $request->trx,
                'payment_id' => $payment->id,
                'order_id'   => $payment->order_id ?? null,
                'status'     => $payment->status,
                'method'     => $payment->method ?? null,
                'amount'     => $payment->amount,
                'error_desc' => $payment->error_description ?? null,
            ]);

            $deposit = \App\Models\Deposit::where('trx', $request->trx)->where('status', 0)->firstOrFail();
            \Log::info('Deposit found. Updating status to 1.');
            $deposit->status = 1; 
            $deposit->save();

            $detail = json_decode($deposit->detail);
            $plan = UserPlan::findOrFail($detail->plan_id);
            $talent = MarketPlace::findOrFail($deposit->marketplace_id);

            // Create Subscription
            MarketSubscription::create([
                'marketplace_id' => $talent->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->plan_name,
                'plan_price' => $plan->plan_price,
                'plan_duration' => $plan->plan_duration,
                'start_date' => now(),
                'end_date' => $plan->plan_duration == 0 ? null : (function() use ($plan) {
                    $d = $plan->plan_duration;
                    $date = now();
                    if ($d == 30 || $d == 1) return $date->addMonth();
                    if ($d == 90 || $d == 3) return $date->addMonths(3);
                    if ($d == 180 || $d == 6) return $date->addMonths(6);
                    if ($d == 365 || $d == 12) return $date->addYear();
                    return $date->addMonths($d);
                })(),
            ]);

            // Featured Logic
            if ($plan->is_featured_plan) {
                $talent->is_featured = true;
                $talent->featured_until = $plan->plan_duration == 0 ? null : (function() use ($plan) {
                    $d = $plan->plan_duration;
                    $date = now();
                    if ($d == 30 || $d == 1) return $date->addMonth();
                    if ($d == 90 || $d == 3) return $date->addMonths(3);
                    if ($d == 180 || $d == 6) return $date->addMonths(6);
                    if ($d == 365 || $d == 12) return $date->addYear();
                    return $date->addMonths($d);
                })();
                $talent->save();
            }

            try {
                app(MailService::class)->sendMailable($talent->email, new MarketplacePlanMail($talent, $plan));
            } catch (\Exception $e) {
                Log::error('Marketplace Plan Email Failed for ' . $talent->email . ': ' . $e->getMessage());
            }

            \Log::info('Marketplace Subscription process completed successfully for talent ID: ' . $talent->id);
            return response()->json(['success' => 'Subscription activated successfully!']);
        } catch (\Exception $e) {
            \Log::channel('razorpay')->error('MARKET | Verification FAILED', [
                'trx'   => $request->trx ?? null,
                'error' => $e->getMessage(),
            ]);
            \Log::error('Razorpay verifyPlanPayment Exception: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()]);
        }
    }
    public function updateService(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'service_brief' => 'nullable|string',
            'service_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userId = session()->get('marketplace_user_id');
        $service = \App\Models\MarketService::where('id', $id)->where('marketplace_id', $userId)->firstOrFail();
        
        $service->service_name = $request->service_name;
        $service->service_brief = $request->service_brief;

        if ($request->hasFile('service_img')) {
            // Delete old
            if ($service->service_img) {
                \App\Helpers\ImageHelper::deleteImage($service->service_img);
            }
            $path = \App\Helpers\ImageHelper::uploadToR2($request->file('service_img'), 'marketplace/services');
            $service->service_img = $path;
        }

        $service->save();
        $notify[] = ['success', 'Service updated successfully.'];
        return back()->withNotify($notify);

    }

    public function updateGallery(Request $request, $id)
    {
        $request->validate([
            'market_gallery' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $userId = session()->get('marketplace_user_id');
        $gallery = \App\Models\MarketGallery::where('id', $id)->where('marketplace_id', $userId)->firstOrFail();
        
        // Delete old
        if ($gallery->market_gallery) {
            \App\Helpers\ImageHelper::deleteImage($gallery->market_gallery);
        }

        $path = \App\Helpers\ImageHelper::uploadToR2($request->file('market_gallery'), 'marketplace/gallery');
        $gallery->market_gallery = $path;
        $gallery->save();

        $notify[] = ['success', 'Photo updated successfully.'];
        return back()->withNotify($notify);

    }

    public function deleteGallery($id)
    {
        $userId = session()->get('marketplace_user_id');
        $gallery = \App\Models\MarketGallery::where('id', $id)->where('marketplace_id', $userId)->firstOrFail();
        
        if ($gallery->market_gallery) {
            \App\Helpers\ImageHelper::deleteImage($gallery->market_gallery);
        }
        
        $gallery->delete();
        $notify[] = ['success', 'Photo removed successfully.'];
        return back()->withNotify($notify);
    }

    public function deleteService($id)
    {
        $userId = session()->get('marketplace_user_id');
        $service = \App\Models\MarketService::where('id', $id)->where('marketplace_id', $userId)->firstOrFail();
        
        if ($service->service_img) {
            \App\Helpers\ImageHelper::deleteImage($service->service_img);
        }
        
        $service->delete();
        $notify[] = ['success', 'Service offering removed successfully.'];
        return back()->withNotify($notify);
    }


    public function deleteContact($id)
    {
        $userId = session()->get('marketplace_user_id');
        $contact = \App\Models\MarketContact::where('id', $id)->where('marketplace_id', $userId)->firstOrFail();
        $contact->delete();
        
        $notify[] = ['success', 'Inquiry removed successfully.'];
        return back()->withNotify($notify);
    }

    /**
     * Display the full gallery of all profile assets.
     */
    public function gallery(Request $request)
    {
        $pageTitle = "Master Collection - Profile Gallery";
        $galleryItems = MarketGallery::whereHas('marketplace', function($q) {
            $q->where('status', 1);
        })->latest()->paginate(24);

        return view('frontend.marketplace.gallery', compact('pageTitle', 'galleryItems'));
    }

    /**
     * Submit a rating for a marketplace profile.
     */
    public function rateMarketplace(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $marketplace = MarketPlace::findOrFail($id);
        
        \App\Models\MarketplaceRating::updateOrCreate([
            'marketplace_id' => $marketplace->id,
            'user_id' => auth()->id()
        ], [
            'rating' => $request->rating
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rating submitted successfully',
            'average_rating' => $marketplace->fresh()->averageRating,
            'total_ratings' => $marketplace->fresh()->totalRatings,
            'breakdown' => $marketplace->fresh()->ratingBreakdown
        ]);
    }

    /**
     * Submit an account deletion request for a marketplace user.
     */
    public function destroyProfile(Request $request)
    {
        $userId = session()->get('marketplace_user_id');
        $talent = MarketPlace::findOrFail($userId);

        $request->validate([
            'reason' => 'required|string',
            'custom_reason' => 'required_if:reason,Other|nullable|string',
            'password' => 'required',
        ], [
            'custom_reason.required_if' => 'Please provide details for choosing "Other".'
        ]);

        if (!Hash::check($request->password, $talent->password)) {
            $notify[] = ['error', 'The provided password does not match our records.'];
            return back()->withNotify($notify)->withInput();
        }

        // Create or update deletion request
        \App\Models\DeletionRequest::updateOrCreate(
            ['marketplace_id' => $talent->id],
            [
                'reason' => $request->reason,
                'custom_reason' => $request->custom_reason,
                'status' => 'pending',
            ]
        );

        // Admin Notification
        try {
            \App\Models\AdminNotification::create([
                'title' => 'New marketplace profile deletion request from ' . $talent->name,
                'click_url' => route('admin.users.deletion.requests'),
            ]);
        } catch (\Exception $e) {
            Log::error("Failed creating admin notification: " . $e->getMessage());
        }

        // Sign out
        Session::forget('marketplace_user_id');

        $notify[] = ['success', 'Your account deletion request has been submitted successfully to the administrator for approval.'];
        return redirect()->route('marketplace.index')->withNotify($notify);
    }
}
