<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{


    public function home()
    {
        return view('home');
    }
	
    public function rooms()
    {
        return view('rooms');
    }
	
    public function room(string $code)
    {
		$room = \App\Models\Room::select('id', 'title', 'code')->where('code', '=', $code)->first();
        return view('room', [
            'code' => $code,
			'room' => $room,
        ]);
    }
	
	public function profile()
    {
		$user = Auth::user();
        return view('profile', [
            'user' => $user,
        ]);
    }


}
