<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedirectLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'link', 'ip_address', 'browser', 'robot'
    ];
}
