<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{

    use GlobalStatus;

    protected $casts = ['config' => 'object'];



    public function storageType(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->type == Status::WASABI_SERVER) {
                $html = '<span class="badge badge--warning">' . trans('Wasabi') . '</span>';
            } elseif ($this->type == Status::DIGITAL_OCEAN_SERVER) {
                $html = '<span class="badge badge--success">' . trans('Digital Ocean') . '</span>';
            } elseif ($this->type == Status::CLOUDFLARE_R2) {
                $html = '<span class="badge badge--info">' . trans('Cloudflare R2') . '</span>';
            } else {
                $html = '<span class="badge badge--dark">' . trans('Ftp') . '</span>';
            }
            return $html;
        });
    }
}
