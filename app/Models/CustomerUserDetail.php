<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\HearAboutUs;

class CustomerUserDetail extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
