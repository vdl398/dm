<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
	
	
	public function updatePhoto($file) {
		if (!$this->id) return false;
		$existFiles = Storage::files('public/profile/'.md5(''.$this->id));
		foreach($existFiles as $existFile) {
		    Storage::delete($existFile);
		}
        $file->store('public/profile/'.md5(''.$this->id));
		return true;
	}
	
	public function getPhoto() {
		if (!$this->id) return '';
		return static::_getPhoto($this->id);
	}
	
	public static function _getPhoto($uid=0) {
		if (!$uid) return '';
		$files = Storage::files('public/profile/'.md5(''.$uid));
		if (!empty($files)) {
			return Storage::url($files[0]);
		}
		return '';
	}
	
	
	
}
