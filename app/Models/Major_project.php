<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Major_project extends Model
{
    protected $table = 'MAJOR_PROJECTS';
    public $sequence = 'MAJOR_PROJECTS_SEQ';
    protected $primaryKey = 'MAJOR_PROS_ID'; // or null

    protected $fillable = ['MAJOR_PROS_ID','MAJOR_DOC_PROS_ID','MAJOR_PROS_SVTYPE','MAJOR_PROS_TARGET_ENDDATE','MAJOR_PROS_LEA','MAJOR_PROS_NAME','MAJOR_PROS_CREATEDATE','MAJOR_PROS_STATUSDATE','MAJOR_PROS_STATUS','MAJOR_PROS_STATUS_REASON' ];
    protected $attributes = [
      
    ];

    public $timestamps = false;
    public $incrementing = false;
    
    use HasFactory;
}

