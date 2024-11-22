<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot_item_allocation extends Model
{
    use HasFactory;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['dia_item_code',  'dia_project_id', 'dia_initqty',  'dia_reqqty',
    'dia_finalqty',  'dia_totused',   'dia_totwaste',   'dia_totcoiled'];
    public $timestamps = false;
}
