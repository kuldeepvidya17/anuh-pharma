<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capa extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'closure_attachment',
    ];

    // Cast specific attributes to array
    protected $casts = [
        
        'closure_attachment' => 'array',
    ];

}
