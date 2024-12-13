<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactUsEntry extends Model
{
    protected $fillable = ['customer_id', 'name', 'email', 'dial_code', 'phone', 'message'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

}
