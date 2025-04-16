<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AjaxResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Validator;

class ProfileController extends Controller
{
	
    public function update(Request $request)
    {
		$user = Auth::user();
		$param = (array)$request->all();
		$validateParam = [
            'email'   => 'required|email',
        ];
		if (!empty($param['password'])) {
			$validateParam['password'] = 'alphaNum|min:3';
			if ($param['password'] != $param['password_confirm']) {
				return back()->withErrors(['password_confirm' => 'confirm error']);
			}
		}

        $this->validate($request, $validateParam);

        $user->email = $param['email'];
		$user->name = $param['name'];
		if (!empty($param['password'])) $user->password = Hash::make($param['password']);
		$user->save();

		$photo = $request->file('photo');
		if ($photo) {
			$user->updatePhoto($photo);
		}

		return redirect('profile')->with('success', 'updateSuccess');
    }
	
	
	
}