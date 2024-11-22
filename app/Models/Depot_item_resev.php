<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot_item_resev extends Model
{
    use HasFactory;
    protected $table = 'DEPOT_ITEM_RESEV';
    protected $primaryKey = null;
    public $incrementing = false;

    //protected $primaryKey = array('dil_project_id','dil_di_id'); // or null

    // public $incrementing = false;
    protected $fillable = ['dil_item_code', 'dil_project_id', 'dil_qty', 'dil_update_by', 'dil_update_date', 'dil_di_id'];
    protected $attributes = [
        'dil_rec_type' => 'ASSIGNED',
    ];
    public $timestamps = false;


    // /**
    //  * Set the keys for a save update query.
    //  *
    //  */
    // protected function setKeysForSaveQuery($query)
    // {
    //     $keys = $this->getKeyName();
    //     if(!is_array($keys)){
    //         return parent::setKeysForSaveQuery($query);
    //     }

    //     foreach($keys as $keyName){
    //         $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
    //     }

    //     return $query;
    // }

    // /**
    //  * Get the primary key value for a save query.
    //  *
    //  */
    // protected function getKeyForSaveQuery($keyName = null)
    // {
    //     if(is_null($keyName)){
    //         $keyName = $this->getKeyName();
    //     }

    //     if (isset($this->original[$keyName])) {
    //         return $this->original[$keyName];
    //     }

    //     return $this->getAttribute($keyName);
    // }
}
