<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_depot_comment extends Model
{
    protected $table = 'PROJECT_DEPOT_COMMENTS';
    protected $primaryKey = null; 

    protected $fillable = ['PDC_ID','PDC_DATE','PDC_PROJECT','PDC_USER','PDC_TEXT'];
    protected $attributes = [
     ];
    public $timestamps = false;
    public $incrementing = false;
    use HasFactory;
}
