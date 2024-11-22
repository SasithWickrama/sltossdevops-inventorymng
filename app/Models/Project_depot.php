<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_depot extends Model
{
    protected $table = 'PROJECT_DEPOT';
    protected $primaryKey = null; // or null

    protected $fillable = ['PD_PROJECT_ID','PD_DEPOT_ID','PD_STATUS_DATE','PD_CREATE_DATE','PD_STATUS'];
    protected $attributes = [
     ];
    public $timestamps = false;
    public $incrementing = false;
    use HasFactory;
}






