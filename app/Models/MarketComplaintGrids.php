<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketComplaintGrids extends Model
{
    use HasFactory;
    protected $table ="market_complaint_grids";
    protected $fillable = [
        'mc_id',
        'identifers',
        'data'
        ];
    
        protected $casts = [
            'data' => 'array'
            // 'aainfo_product_name' => 'array',
               ];
    
          public function MarketComplaint()
               {
                   return $this->hasMany(MarketComplaint::class);
               }
}