<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    protected $table = 'Depot';
    public $sequence = 'DEPOT_SEQ';
    protected $primaryKey = 'DEPOT_ID'; // or null

    protected $fillable = ['DEPOT_ID','DEPOT_CRESTE_DATE','DEPOT_STATUS','DEPOT_STATUS_DATE','DEPOT_USER_NAME','DEPOT_ERP_REF','DEPOT_USER_ADDRESS','DEPOT_REMARKS','DEPOT_TYPE'];
    protected $attributes = [
    ];

    public $timestamps = false;
    public $incrementing = false;
    
    use HasFactory;
}
