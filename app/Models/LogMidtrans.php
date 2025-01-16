<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogMidtrans extends Model
{
    use HasFactory;

    const REQUEST = 'req';
    const CALLBACK = 'call';

    protected $guarded = ['id'];
}
