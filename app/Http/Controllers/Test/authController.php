<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class authController extends Controller
{
   public function __construct() {
      $this->middleware('admin');
   }
   
   public function index() {
      echo "<br>Test Controllfdgfdgder.";
   }
   
   public function index2() {
      echo "<br>Test index 2.";
   }
   
   
   
    //
}
