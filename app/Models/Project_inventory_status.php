<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_inventory_status extends Model
{
    use HasFactory;
    protected $table = 'PROJECTS_INVENTORY_STATUS';
    protected $primaryKey = null;
    public $incrementing = false;
}
