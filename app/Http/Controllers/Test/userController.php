<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class userController extends Controller {
   public function index() {
      echo "<br>Test Controller.";
   }
   public function showProfile() {
      echo "<br>Test profile.";
   }
   
   public function showPath(Request $request) {
      $uri = $request->path();
      echo '<br>URI: '.$uri;
      
      $url = $request->url();
      echo '<br>';
      
      echo 'URL: '.$url;
      $method = $request->method();
      echo '<br>';
      
      echo 'Method: '.$method;
   }
   
    public function register(Request $request) {
      $uri = $request->path();
      echo '<br>URI: '.$uri;
      
      $url = $request->url();
      echo '<br>';
      
      echo 'URL: '.$url;
      $method = $request->method();
      echo '<br>';
      
      echo 'Method: '.$method;
   }
   
   public function details()
   {
       echo "hello";
      return view('admin.details');  
   }
}
