<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SupportAttachment extends Model
{
    protected $appends = ['encrypted_id'];

    public function supportMessage()
    {
        return $this->belongsTo(SupportMessage::class,'support_message_id');
    }

    public function encryptedId(): Attribute
    {
        return new Attribute(
            get: fn () => encrypt($this->id),
        );
    }

    public function isImage(): Attribute
    {
        return new Attribute(
            get: function () {
                $ext = pathinfo($this->attachment, PATHINFO_EXTENSION);
                return in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            }
        );
    }

    public function downloadUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                if (auth()->guard('admin')->check()) {
                    return route('admin.ticket.download', $this->encrypted_id);
                }
                return route('user.ticket.download', $this->encrypted_id);
            },
        );
    }
}
