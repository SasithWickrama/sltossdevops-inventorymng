<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_hierarchy extends Model
{
    protected $table = 'PROJECT_HIERARCHY';
    protected $primaryKey = null; // or null

    protected $fillable = ['PARENT_ID','CHILD_ID'];
    // protected $attributes = [
    //     'PA_ID' => '1',
    //     'PA_NAME' => 'CONTRACTOR',
    //     'PA_ATTID' => '1',
    //     'PA_ORDER' => '1'
    //  ];
    public $timestamps = false;
    public $incrementing = false;
    use HasFactory;
}






