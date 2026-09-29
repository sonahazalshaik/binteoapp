<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\PasswordResetMail;
use App\Services\MailService;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, UserNotify;

    /**
     * Disable mass-assignment protection since validation is handled in controllers.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'ver_code', 'balance', 'kyc_data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'kyc_data'          => 'object',
        'advertiser_data'   => 'object',
        'social_links'      => 'array',
        'ver_code_send_at'  => 'datetime',
        'last_seen'         => 'datetime',
    ];

    protected ?bool $cachedFeaturedAccess = null;

    public function loginLogs() {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function deposits() {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function withdrawals() {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function tickets() {
        return $this->hasMany(SupportTicket::class);
    }

    public function videos() {
        return $this->hasMany(Video::class);
    }

    public function memberships() {
        return $this->hasManyThrough(Membership::class, Channel::class, 'user_id', 'channel_id');
    }



    public function subscribers() {
        return $this->hasManyThrough(Subscription::class, Channel::class, 'user_id', 'channel_id');
    }

    public function subscriptions() {
        return $this->belongsToMany(Channel::class, 'subscriptions', 'user_id', 'channel_id')->withPivot('notification_preference')->withTimestamps(); // People this user is subscribed to
    }

    public function videoImpression() {
        return $this->hasMany(Impression::class);
    }

    public function watchHistories() {
        return $this->hasMany(WatchHistory::class);
    }

    public function purchasedVideos() {
        return $this->hasMany(PurchasedVideo::class);
    }

    public function saleVideos() {
        return $this->hasMany(PurchasedVideo::class, 'owner_id');
    }

    public function purchasedPlaylists() {
        return $this->hasMany(PurchasedPlaylist::class);
    }

    public function purchasedPlans() {
        return $this->hasMany(PurchasedPlan::class);
    }

    public function hasValidPlan($planId) {
        $purchase = $this->purchasedPlans()->where('plan_id', $planId)->where('user_id', auth()->id())->latest()->first();

        if (!$purchase) {
            return false;
        }

        return is_null($purchase->expired_date) || now()->lt($purchase->expired_date);
    }

    public function ottSubscriptions() {
        return $this->hasMany(OttSubscription::class);
    }

    public function hasPremiumAccess() {
        return $this->hasPlanAccess() || $this->hasOttAccess();
    }

    public function hasPlanAccess() {
        return $this->purchasedPlans()->whereHas('plan', function($q) {
            $q->where('video_access', 1);
        })->where(function($q) {
            $q->where('expired_date', '>', now())->orWhereNull('expired_date');
        })->exists();
    }

    public function hasOttAccess() {
        return $this->ottSubscriptions()->where('status', 1)->where(function($q) {
            $q->where('end_date', '>', now())->orWhereNull('end_date');
        })->exists();
    }

    public function hasFeaturedAccess(): bool {
        if ($this->cachedFeaturedAccess !== null) {
            return $this->cachedFeaturedAccess;
        }
        $this->cachedFeaturedAccess = $this->purchasedPlans()->whereHas('plan', function($q) {
            $q->where('is_featured_plan', 1);
        })->where(function($q) {
            $q->where('expired_date', '>', now())->orWhereNull('expired_date');
        })->exists();
        return $this->cachedFeaturedAccess;
    }

    public function isPurchased($videoId) {
        return $this->purchasedVideos()->where('video_id', $videoId)->exists();
    }

    public function salePlaylists() {
        return $this->hasMany(PurchasedPlaylist::class, 'owner_id');
    }

    public function salePlans() {
        return $this->hasMany(PurchasedPlan::class, 'owner_id');
    }

    public function watchLaters() {
        return $this->playlists()->where('name', 'Watch Later')->first()?->videos() ?: $this->videos()->whereRaw('1=0');
    }

    public function notInterestedVideos() {
        return $this->hasMany(NotInterestedVideo::class);
    }

    public function videoEarnings() {
        return $this->hasManyThrough(VideoEarning::class, Video::class);
    }

    public function withdrawSetting() {
        return $this->belongsTo(WithdrawSetting::class, 'id', 'user_id');
    }

    public function advertisements() {
        return $this->hasMany(Advertisement::class);
    }

    public function userReactions() {
        return $this->hasMany(UserReaction::class);
    }

    public function userLikes() {
        return $this->hasMany(Like::class)->where('video_id', '!=', 0);
    }

    public function userDislikes() {
        return $this->hasMany(Like::class)->whereRaw('1=0');
    }

    public function fullname(): Attribute {
        return new Attribute(
            get: function () {
                $nameFromFirstLast = trim(($this->firstname ?? '') . ' ' . ($this->lastname ?? ''));
                if (!empty($nameFromFirstLast)) {
                    return $nameFromFirstLast;
                }
                if (!empty($this->name)) {
                    return trim($this->name);
                }
                return $this->username ?? $this->email ?? 'User';
            }
        );
    }

    public function mobileNumber(): Attribute {
        return new Attribute(
            get: fn() => $this->dial_code . $this->mobile,
        );
    }
    public function purchasedVideoId(): Attribute {
        return new Attribute(
            get: fn() => $this->purchasedVideos->pluck('video_id')->toArray(),
        );
    }
    public function purchasedPlaylistId(): Attribute {
        return new Attribute(
            get: fn() => $this->purchasedPlaylists->pluck('playlist_id')->toArray(),
        );
    }
    public function watchLatterVideoId(): Attribute {
        return new Attribute(
            get: fn() => $this->watchLaters->pluck('video_id')->toArray(),
        );
    }

    // --- Restored Original new-youtube Relationships ---
    
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reelComments()
    {
        return $this->hasMany(ReelComment::class);
    }

    public function likedVideos()
    {
        return $this->belongsToMany(Video::class, 'likes');
    }

    public function viewLogs()
    {
        return $this->hasMany(ViewLog::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function purchasedMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    public function reels()
    {
        return $this->hasMany(Reel::class);
    }

    public function reelLikes()
    {
        return $this->hasMany(ReelLike::class);
    }

    public function likedReels()
    {
        return $this->belongsToMany(Reel::class, 'reel_likes')->wherePivot('is_like', 1);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    /**
     * Check if the user is a creator (purchased a creator plan and has creator_status enabled).
     */
    public function isCreator(): bool
    {
        return $this->role === 'user' && $this->creator_status == 1;
    }

    // --- SCOPES ---
    public function scopeActive($query) {
        return $query->where('status', Status::USER_ACTIVE);
    }

    public function scopeBanned($query) {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query) {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query) {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query) {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query) {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query) {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query) {
        return $query->where('sv', Status::VERIFIED);
    }

    public function scopeWithBalance($query) {
        return $query->where('balance', '>', 0);
    }

    public function scopeMonetizationRequest($query) {
        return $query->where('monetization_status', Status::MONETIZATION_APPLYING);
    }

    public function scopeCreators($query) {
        return $query->where('creator_status', 1);
    }

    public function scopeRegularUsers($query) {
        return $query->where('creator_status', 0);
    }

    public function scopeMonetizationApproved($query) {
        return $query->where('monetization_status', Status::MONETIZATION_APPROVED);
    }

    public function scopePendingAdvertisers($query) {
        return $query->where('advertiser_status', Status::ADVERTISER_PENDING);
    }

    public function scopeApprovedAdvertisers($query) {
        return $query->where('advertiser_status', Status::ADVERTISER_APPROVED);
    }

    public function scopeRejectedAdvertisers($query) {
        return $query->where('advertiser_status', Status::ADVERTISER_REJECTED);
    }

    public function deviceTokens() {
        return $this->hasMany(DeviceToken::class);
    }



    public function isSubscribe() {
        $subscriptions = $this->subscriptions()->pluck('channel_id')->toArray();
        return $subscriptions;
    }

    public function advertiseStatus(): Attribute {

        return new Attribute(function () {
            $html = '';
            if ($this->advertiser_status == Status::ADVERTISER_APPROVED) {
                $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
            } else if ($this->advertiser_status == Status::ADVERTISER_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } else {
                $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
            }
            return $html;
        });
    }

    public function monetizationStep(): Attribute {

        return new Attribute(function () {
            $html = '';
            if ($this->monetization_status == Status::MONETIZATION_APPLYING) {
                $html = '<span class="badge badge--warning">' . trans('Applying') . '</span>';
            } else if ($this->monetization_status == Status::MONETIZATION_APPROVED) {
                $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
            } else if ($this->monetization_status == Status::MONETIZATION_CANCEL) {
                $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
            }
            return $html;
        });
    }

    public function firebaseTokens() {
        return $this->hasMany(FirebaseToken::class, 'user_id');
    }

    public function copyrightStrikes()
    {
        return $this->hasMany(CopyrightStrike::class);
    }

    /**
     * Override the default password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        try {
            app(MailService::class)->sendMailable($this->email, new PasswordResetMail($this, $token));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Password Reset Email Failed for ' . $this->email . ': ' . $e->getMessage());
        }
    }

    /**
     * Get the appropriate profile/channel URL for the user.
     */
    public function getProfileUrl()
    {
        if ($this->username) {
            return route('channels.show_by_username', ['username' => $this->username]);
        }

        if ($this->channel) {
            return route('channels.show', $this->channel->id);
        }

        return '#';
    }

    /**
     * Safely decrypt and return the plain-text password if it exists.
     */
    public function getUnlockedPassword()
    {
        if (!$this->password_text) {
            return null;
        }

        try {
            return decrypt($this->password_text);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function deletionRequest()
    {
        return $this->hasOne(DeletionRequest::class);
    }
}
