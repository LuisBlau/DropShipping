<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebaysellerproduct extends Model
{
    use HasFactory;
    protected $table = 'ebaysellerproducts'; // Enter the table name here
    protected $primaryKey = 'id'; // Enter the primary key here
    protected $fillable = ['title', 'itemid', 'uuid', 'startprice', 'currency', 'quantity', 'quantitysold', 'autopay', 'country'];
}