<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapaGrid extends Model
{
    use HasFactory;
    protected $table ="capa_grids";
    protected $fillable = [
        'capa_id',
        'identifers',
        'data'
        ];
    
        protected $casts = [
            'data' => 'array'
            // 'aainfo_product_name' => 'array',
               ];

               public function Capa()
               {
                   return $this->hasMany(Capa::class);
               }
}
