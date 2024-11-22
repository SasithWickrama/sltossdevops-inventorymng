<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workgroup extends Model
{

    protected $fillable = ['WGINVENTORY_ID'];

    public $timestamps = false;
    public $incrementing = false;

    use HasFactory;
}
