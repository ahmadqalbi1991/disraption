<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    // Disable timestamps
    public $timestamps = false;
    
    protected $fillable = ['meta_key','meta_value'];


}
