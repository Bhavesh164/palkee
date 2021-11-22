<?php

namespace App\Model\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class complaintModel extends Model
{
    protected $table = 'complaint';
    protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $fillable = ['full_name',''];
    protected $guarded = [];
}
