<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot_item_log extends Model
{
    use HasFactory;
    protected $table = 'DEPOT_ITEM_LOG';
    protected $primaryKey = 'DIL_ID';
    protected $fillable = ['dil_di_id','dil_project_id','dil_qty','dil_update_by','dil_update_date','dil_rec_type'];
    public $timestamps = false;
}
