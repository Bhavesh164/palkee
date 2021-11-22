<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    //
     protected $table = 'admin';
     protected $primaryKey = 'admin_id';
     public $timestamps = false;
}
