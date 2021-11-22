<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('ID/{id}',function($id) {
   echo 'ID: '.$id;
});
//Route::get('user/{name?}', function ($name = 'TutorialsPoint') { 
//    echo  $name;
//    
//});
Route::get('user/profile', 'UserController@showProfile')->name('profile');

Route::get('admin/login', 'authController@index')->name('profile');

Route::get('role',[
   'middleware' => 'Role:editor',
   'uses' => 'TestController@index',
]);

Route::get('admin/index',[
   'middleware' => 'admin',
   'uses' => 'authController@index2',
]);

Route::get('/usercontroller/path',[
   'middleware' => 'admin',
   'uses' => 'UserController@showPath'
]);

// for crud //
Route::resource('my','MyController');

// single route for all controller //
//Route::controller('test','ImplicitController');

//class MyClass{
//   public $foo = 'bar';
//}
//Route::get('/myclass','ImplicitController@index');

//Route::post('test','MyController');

Route::get('/register',function() {
   return view('register');
});
Route::post('/user/register',array('uses'=>'UserRegistration@postRegister'));

Route::get('/cookie/set','CookieController@setCookie');
Route::get('/cookie/get','CookieController@getCookie');

Route::get('/basic_response', function () {
   return 'Hello World response';
});

Route::get('/header',function() {
   return response("Hello", 200)->header('Content-Type', 'text/html');
});

Route::get('/cookie',function() {
   return response("Hello", 200)->header('Content-Type', 'text/html')
      ->withcookie('name','Virat Gandhi');
});

Route::get('json',function() {
   return response()->json(['name' => 'Virat Gandhi', 'state' => 'Gujarat']);
});

//Route::get('/test', function() {
//   return view('test');
//});

Route::get('/test', function() {
   return view('test',['name'=>'Virat Gandhi']);
});

Route::get('/test2', function() {
   return view('test');
});


Route::get('/user/detail','UserController@details');

Route::get('/about', function () 
{  
    return view('about');  
}); 