<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beautyfortstockold extends Model
{
    use HasFactory;
    protected $table = 'beautyfortstocks_old'; // Enter the table name here
    protected $primaryKey = 'id'; // Enter the primary key here
    protected $fillable = ['stock_code', 'full_name', 'stock_level', 'rrp', 'price', 'last_purchased_price', 'barcode', 'collection', 'high_res_image_url', 'brand', 'quantity', 'type', 'size', 'gender', 'category'];    
}
