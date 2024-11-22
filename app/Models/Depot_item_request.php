<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot_item_request extends Model
{
    protected $table = 'DEPOT_ITEM_REQUESTS';
    protected $primaryKey = null;
    public $incrementing = false;

   // public $incrementing = false;
    protected $fillable = ['dil_item_code','dil_project_id','dil_qty','dil_update_by','dil_update_date'];
    protected $attributes = [
        'dil_rec_type' => 'REQUEST',
        'dil_resv_qty' => 0
     ];
    public $timestamps = false;
    use HasFactory;

    public function Item()
    {
        return $this->belongsTo('App\Models\Item');

    }
}
