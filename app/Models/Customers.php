<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $table = 'customers';

    public $primaryKey = 'customerid';
    public $timestamps = false;
}
