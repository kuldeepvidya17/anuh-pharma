<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionsPlan extends Model
{
    use HasFactory;
    protected $table = 'actions_plans';

    protected $casts = [
        'data' => 'array'
    ];
    
}
