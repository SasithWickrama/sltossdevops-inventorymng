<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    protected $table = 'PROJECTS';
    public $sequence = 'PROJECTS_SEQ';
    protected $primaryKey = 'PROS_ID'; // or null

    protected $fillable = ['PROS_ID','PROS_SVTYPE','PROS_TYPE','PROS_TARGET_ENDDATE','PROS_LEA','PROS_NAME','PROS_CREATEDATE','PROS_STATUSDATE'];
    protected $attributes = [
        'PROS_STATUS' => 'INITIATED',
        'PROS_STATUS_REASON' => 'ADD NEW PROJECT'
    ];

    public $timestamps = false;
    public $incrementing = false;
    
    use HasFactory;
}

