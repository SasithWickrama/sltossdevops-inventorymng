<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_project_item_allocation extends Model
{
    use HasFactory;
    protected $fillable = ['PIA_ITEM_CODE',
    'PIA_QTY',
    'PIA_PROJECT_ID',
    'PIA_UPDATE_DATE',
    'PIA_UPDATE_BY'];
    public $timestamps = false;
    //protected $primaryKey = null;
    public $incrementing = false;
}
