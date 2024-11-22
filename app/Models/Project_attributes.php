<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_attributes extends Model
{
    protected $primaryKey = null; // or null

    protected $fillable = ['PA_PROJECT_ID','PA_VALUE','PA_OLD_VALUE','PA_LAST_UPDATE','PA_UPDATE_BY'];
    protected $attributes = [
        'PA_NAME' => 'CONTRACTOR',
        'PA_ATTID' => '1',
        'PA_ORDER' => '1'
     ];
    public $timestamps = false;
    public $incrementing = false;
    use HasFactory;
}






