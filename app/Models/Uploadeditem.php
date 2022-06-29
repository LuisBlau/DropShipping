<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uploadeditem extends Model
{
    use HasFactory;
    protected $table = 'uploadeditems'; // Enter the table name here
    protected $primaryKey = 'id'; // Enter the primary key here
    protected $fillable = ['stock_code', 'itemid', 'full_name', 'price', 'collection', 'high_res_image_url', 'brand', 'category'];    
}