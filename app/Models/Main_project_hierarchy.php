<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Main_project_hierarchy extends Model
{
    protected $table = 'MAIN_PROJECT_HIERARCHY';
    
    protected $fillable = ['PROJECT_ID','JOB_ID','JOB_TYPE'];
    protected $attributes = [
      
    ];

    public $timestamps = false;
    public $incrementing = false;
    
    use HasFactory;
}

