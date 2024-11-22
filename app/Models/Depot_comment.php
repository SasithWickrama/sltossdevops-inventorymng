<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot_comment extends Model
{
    protected $table = 'DEPOT_COMMENTS';
    protected $primaryKey = null; 

    protected $fillable = ['DC_ID','DC_DATE','DC_DEPOT','DC_USER','DC_TEXT'];
    protected $attributes = [
     ];
    public $timestamps = false;
    public $incrementing = false;
    use HasFactory;
}
