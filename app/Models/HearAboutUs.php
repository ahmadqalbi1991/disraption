<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HearAboutUs extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $fillable = [
        'name',
        'active',
        'deleted',
        'created_at',
        'updated_at',
    ];
    
}