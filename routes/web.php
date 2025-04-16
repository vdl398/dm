<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Middleware;


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

Route::get('/', [Controllers\PageController::class, 'home'])->name('home');

//страница списка комнат
Route::get('/rooms', [Controllers\PageController::class, 'rooms'])->middleware('auth');

//страница комнаты
Route::get('/rooms/{id}', [Controllers\PageController::class, 'room']);

//настройки профиля
Route::get('/profile', [Controllers\PageController::class, 'profile'])->middleware('auth');

//сохранить профиль
Route::post('/profile', [Controllers\ProfileController::class, 'update'])->middleware('auth');

//проверка авторизации
Route::get('/auth', [Controllers\UserController::class, 'auth']);
Route::get('/login', function () {
    return view('auth.login');
});
Route::post('/login', [Controllers\UserController::class, 'login']);


Route::get('/logout', [Controllers\UserController::class, 'logout']);

//регистрация
Route::get('/register', [Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [Controllers\Auth\RegisterController::class, 'registerAjax']);



//создать комнату ajax
Route::post('/room/create', [Controllers\RoomController::class, 'create'])->middleware(Middleware\AjaxAuth::class);

//удалить комнату ajax
Route::post('/room/delete', [Controllers\RoomController::class, 'deleteRoom'])->middleware(Middleware\AjaxAuth::class);

//список комнат ajax
Route::get('/room/list', [Controllers\RoomController::class, 'getList']);


//ниже роуты для ajax запросов на странице комнаты

//полчить детальные данные комнаты
Route::get('/room/item', [Controllers\RoomController::class, 'getItem'])->middleware(Middleware\AjaxAuth::class);

//проврека websocket сервера на node.js, для организации сеанса webrts между участниками и тд.
Route::get('/room/checksocket', [Controllers\RoomController::class, 'checkSocket'])->middleware(Middleware\AjaxAuth::class);

//добавить участника комнаты
Route::post('/room/addmember', [Controllers\RoomController::class, 'addMember']);

//удалить участника
Route::get('/room/deletemember', [Controllers\RoomController::class, 'deleteMember'])->middleware(Middleware\AjaxAuth::class);

//добавить сообщение в чат
Route::post('/room/addmessage', [Controllers\RoomController::class, 'addMessage'])->middleware(Middleware\AjaxAuth::class);

//получить сообщения чата
Route::get('/room/getmessages', [Controllers\RoomController::class, 'getMessages'])->middleware(Middleware\AjaxAuth::class);



