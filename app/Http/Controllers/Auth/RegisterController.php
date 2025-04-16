<?php

namespace App\Http\Controllers\Auth;



use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Resources\AjaxResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;



class RegisterController extends Controller
{

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }
	
    public function registerAjax(Request $request)
    {
        $resource = new AjaxResource();
		$param = (array)$request->json()->all();
        $this->validator($param)->validate();
        $user = User::create([
            'name' => $param['name'],
            'email' => $param['email'],
            'password' => Hash::make($param['password']),
        ]);
		if (!$user || !(int)$user->id) {
			return response()->json(['errors' => ['email' => 'user create error']],401);
		}
        event(new Registered($user));
		Auth::login($user);
		return $resource->sendSuccess();
    }
	
	
    public function showRegistrationForm()
    {
        return view('auth.register');
    }


}
