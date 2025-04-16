<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AjaxResource;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class UserController extends Controller
{
    
	
    public function auth(Request $request){
		$resource = new AjaxResource();
		$data = [
			'user' => [
			    'id' => 0,
			    'login' => '',
			    'name' => '',
			    'last_name' => '',
			    'second_name' => '',
			],
		];
		if (Auth::check()) {
			$user = Auth::user();
			$data['user']['id'] = (int)$user['id'];
			$data['user']['login'] = $user['email'];
			$data['user']['name'] = $user['name'];
			$data['user']['last_name'] = '';
			$data['user']['second_name'] = '';
			$data['user']['photo'] = $user->getPhoto() ?: '/images/default-avatar.jpg';
			return $resource->sendSuccess($data);
		}
		return $resource->sendError(['auth error']);
    }

    public function login(Request $request){
		$resource = new AjaxResource();
        $param = (array)$request->json()->all();

        //создаем временного пользователя обычно при заходе в комнату незалогиненным
		if (!empty($param['tmp']) && ($param['tmp'] == 'Y')) {
            $user = User::create([
                'name' => $param['name'],
                'email' => md5($param['name'].'-'.date('YmdHis').'-'.rand(1, 99999)).'@mail.ru',
                'password' => Hash::make('gfhfghfgdfdghfgd'),
            ]);
		    if (!(int)$user->id) {
			    return $resource->sendError(['name' => 'error']);
		    }
            event(new Registered($user));
            Auth::login($user);
			return $resource->sendSuccess();
		}
        if (Auth::attempt($param)) {
            $request->session()->regenerate();
            return $resource->sendSuccess();
        }
		return $resource->sendError(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

	
}
