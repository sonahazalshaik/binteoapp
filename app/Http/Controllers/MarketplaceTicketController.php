<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\MarketPlace;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MarketplaceTicketController extends Controller
{
    protected $allowedExtension = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
    protected $files;

    protected function marketplace(): MarketPlace
    {
        $id = Session::get('marketplace_user_id');
        return MarketPlace::findOrFail($id);
    }

    protected function baseQuery()
    {
        return SupportTicket::where('marketplace_id', Session::get('marketplace_user_id'));
    }

    protected function wantsGridJson(Request $request): bool
    {
        if (!$request->hasHeader('X-Ticket-List')) {
            return false;
        }
        if ($request->header('Sec-Fetch-Mode') === 'navigate' || $request->header('Sec-Fetch-Dest') === 'document') {
            return false;
        }
        return $request->ajax() && $request->wantsJson();
    }

    public function ticketIndex()
    {
        $marketplace = $this->marketplace();
        $query = $this->baseQuery();

        if (request()->has('status') && request()->status !== '' && request()->status !== null && request()->status !== 'all') {
            $query->where('status', request()->status);
        }

        $supports = $query->orderBy('priority', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate())
            ->appends(request()->query());

        $all = $this->baseQuery();
        $counts = [
            'all'         => (clone $all)->count(),
            'pending'     => (clone $all)->where('status', 0)->count(),
            'answered'    => (clone $all)->where('status', 1)->count(),
            'in_progress' => (clone $all)->where('status', 2)->count(),
            'closed'      => (clone $all)->where('status', 3)->count(),
            'rejected'    => (clone $all)->where('status', 4)->count(),
        ];

        $pageTitle = 'My Support Tickets';

        return view('frontend.marketplace.ticket.index', compact('supports', 'counts', 'pageTitle', 'marketplace'));
    }

    public function ticketGrid()
    {
        if (!$this->wantsGridJson(request())) {
            abort(404);
        }

        $query = $this->baseQuery();

        if (request()->has('status') && request()->status !== '' && request()->status !== null && request()->status !== 'all') {
            $query->where('status', request()->status);
        }

        $supports = $query->orderBy('priority', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate())
            ->appends(request()->query());

        $all = $this->baseQuery();
        $counts = [
            'all'         => (clone $all)->count(),
            'pending'     => (clone $all)->where('status', 0)->count(),
            'answered'    => (clone $all)->where('status', 1)->count(),
            'in_progress' => (clone $all)->where('status', 2)->count(),
            'closed'      => (clone $all)->where('status', 3)->count(),
            'rejected'    => (clone $all)->where('status', 4)->count(),
        ];

        return response()->json([
            'html' => view('frontend.marketplace.ticket.partials.ticket_list', compact('supports'))->render(),
            'counts' => $counts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function ticketCreate()
    {
        $marketplace = $this->marketplace();
        $pageTitle = 'New Support Ticket';

        return view('frontend.marketplace.ticket.create', compact('pageTitle', 'marketplace'));
    }

    public function ticketStore(Request $request)
    {
        $marketplace = $this->marketplace();

        $request->validate($this->validation($request));

        $ticket = new SupportTicket();
        $ticket->marketplace_id = $marketplace->id;
        $ticket->user_id = null;
        $ticket->ticket = rand(100000, 999999);
        $ticket->name = $marketplace->name;
        $ticket->email = $marketplace->email;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = $request->status ?? Status::TICKET_OPEN;
        $ticket->priority = $request->priority;
        $ticket->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = 0;
        $adminNotification->marketplace_id = $marketplace->id;
        $adminNotification->title = 'New marketplace support ticket has opened';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        try {
            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Jobs\SendFirebaseNotificationJob::dispatch($admin->id, [
                    'title' => 'New Marketplace Support Ticket',
                    'body' => 'A new support ticket has been opened by ' . $marketplace->name,
                    'url' => urlPath('admin.ticket.view', $ticket->id),
                ], 'admin');
            }
        } catch (\Exception $e) {
            \Log::error('Admin Marketplace Ticket Notification Dispatch Failed: ' . $e->getMessage());
        }

        if ($request->hasFile('attachments')) {
            $upload = $this->storeSupportAttachments($message->id);
            if ($upload != 200) {
                return back()->withNotify($upload);
            }
        }

        $notify[] = ['success', 'Ticket opened successfully!'];

        return to_route('marketplace.ticket.index')->withNotify($notify);
    }

    public function ticketView($id)
    {
        $marketplace = $this->marketplace();
        $pageTitle = 'View Ticket';

        $myTicket = $this->baseQuery()
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('ticket', $id);
            })->firstOrFail();

        $messages = SupportMessage::where('support_ticket_id', $myTicket->id)
            ->with('attachments')
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend.marketplace.ticket.view', compact('myTicket', 'messages', 'pageTitle', 'marketplace'));
    }

    public function ticketReply(Request $request, $id)
    {
        $marketplace = $this->marketplace();

        $ticket = $this->baseQuery()->where('id', $id)->first();
        if (!$ticket) {
            abort(404);
        }

        $request->merge(['ticket_reply' => 1]);
        $request->validate($this->validation($request));

        $ticket->status = Status::TICKET_REPLY;
        $ticket->last_reply = Carbon::now();
        $ticket->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        if ($request->hasFile('attachments')) {
            $upload = $this->storeSupportAttachments($message->id);
            if ($upload != 200) {
                return back()->withNotify($upload);
            }
        }

        try {
            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Jobs\SendFirebaseNotificationJob::dispatch($admin->id, [
                    'title' => 'Marketplace Support Ticket Reply',
                    'body' => $marketplace->name . ' replied to ticket: ' . $ticket->subject,
                    'url' => urlPath('admin.ticket.view', $ticket->id),
                ], 'admin');
            }
        } catch (\Exception $e) {
            \Log::error('Admin Marketplace Ticket Reply Notification Dispatch Failed: ' . $e->getMessage());
        }

        $notify[] = ['success', 'Support ticket replied successfully!'];

        return back()->withNotify($notify);
    }

    public function ticketClose($id)
    {
        $ticket = $this->baseQuery()->where('id', $id)->first();
        if (!$ticket) {
            abort(403);
        }

        $ticket->status = Status::TICKET_CLOSE;
        $ticket->save();

        $notify[] = ['success', 'Support ticket closed successfully!'];

        return back()->withNotify($notify);
    }

    public function ticketHubData($id)
    {
        $marketplace = $this->marketplace();

        $ticket = $this->baseQuery()
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('ticket', $id);
            })->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $messages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->with('attachments')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($msg) use ($marketplace) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_admin' => (bool) $msg->admin_id,
                    'sender' => $msg->admin_id ? 'Support Analyst' : $marketplace->name,
                    'created_at' => $msg->created_at->diffForHumans(),
                    'attachments' => $msg->attachments->map(fn ($a) => [
                        'id' => $a->encrypted_id,
                        'url' => route('marketplace.ticket.download', $a->encrypted_id),
                        'is_image' => $a->is_image,
                        'image_url' => $a->is_image ? getImage($a->attachment) : null,
                    ]),
                ];
            });

        return response()->json([
            'ticket' => [
                'id' => $ticket->id,
                'ticket' => $ticket->ticket,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
            ],
            'messages' => $messages,
        ]);
    }

    public function ticketDownload($attachmentId)
    {
        // Ownership check: attachment must belong to one of this marketplace's tickets.
        $attachment = SupportAttachment::find(decrypt($attachmentId));
        if (!$attachment) {
            abort(404);
        }

        $ticketId = $attachment->supportMessage?->support_ticket_id;
        $owned = $ticketId ? $this->baseQuery()->where('id', $ticketId)->exists() : false;
        if (!$owned) {
            abort(403);
        }

        $file = $attachment->attachment;
        $title = slug($attachment->supportMessage->ticket->subject);
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (strpos($file, 'http') === 0) {
            $fullUrl = substr($file, strpos($file, 'http'));
            $r2 = gs('cloudflare_config');
            $endpoint = @$r2->endpoint ?: config('filesystems.disks.r2.endpoint');
            $bucket = @$r2->bucket ?: config('filesystems.disks.r2.bucket');
            $customUrl = @$r2->url ?: config('filesystems.disks.r2.url');
            $relativePath = null;
            if ($customUrl && strpos($fullUrl, $customUrl) === 0) {
                $relativePath = substr($fullUrl, strlen(rtrim($customUrl, '/')) + 1);
            } elseif ($endpoint) {
                $baseToRemove = rtrim($endpoint, '/') . '/' . $bucket . '/';
                $relativePath = str_replace($baseToRemove, '', $fullUrl);
                if ($relativePath === $fullUrl) {
                    $cleanEndpoint = preg_replace('/^https?:\/\//', '', $endpoint);
                    $cleanUrl = preg_replace('/^https?:\/\//', '', $fullUrl);
                    $baseToRemove = $cleanEndpoint . '/' . $bucket . '/';
                    $relativePath = str_replace($baseToRemove, '', $cleanUrl);
                }
            }
            if ($relativePath && $relativePath !== $fullUrl) {
                $r2Path = \App\Helpers\ImageHelper::getR2Path($relativePath);

                return redirect(\Illuminate\Support\Facades\Storage::disk('r2')->temporaryUrl($r2Path, now()->addHours(1), [
                    'ResponseContentDisposition' => 'attachment; filename="' . $title . '.' . $ext . '"',
                ]));
            }

            return redirect(\App\Helpers\ImageHelper::getPhotoUrl($fullUrl));
        }

        $path = getFilePath('ticket');
        $fullPath = $path . '/' . $file;
        if (!file_exists($fullPath)) {
            $notify[] = ['error', 'Attachment not found'];

            return back()->withNotify($notify);
        }
        $mimetype = mime_content_type($fullPath);

        if (!headers_sent()) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Content-Type');
        }
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header('Content-Type: ' . $mimetype);

        return readfile($fullPath);
    }

    protected function storeSupportAttachments($messageId)
    {
        foreach ($this->files as $file) {
            try {
                $attachment = new SupportAttachment();
                $attachment->support_message_id = $messageId;
                $attachment->attachment = \App\Helpers\ImageHelper::uploadToR2($file, 'ticket');
                $attachment->save();
            } catch (\Exception $exp) {
                $notify[] = ['error', 'File could not upload'];

                return $notify;
            }
        }

        return 200;
    }

    protected function validation(Request $request)
    {
        $this->files = $request->file('attachments');

        return [
            'attachments' => [
                function ($attribute, $value, $fail) {
                    if (!$this->files) {
                        return;
                    }
                    foreach ($this->files as $file) {
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (!in_array($ext, $this->allowedExtension)) {
                            return $fail('Only ' . implode(', ', $this->allowedExtension) . ' files are allowed');
                        }
                    }
                    if (count($this->files) > 4) {
                        return $fail('Maximum 4 files can be uploaded');
                    }
                },
            ],
            'subject' => 'required_without:ticket_reply|max:255',
            'priority' => 'required_without:ticket_reply|in:1,2,3',
            'message' => 'required',
        ];
    }
}
