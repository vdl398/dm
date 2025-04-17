<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

//сущность чата комнаты
class RoomMessages extends Model
{
    use HasFactory;
	
	protected $table = 'room_messages';
	
	
	
	public $name = '';
	public $image = '';
	public $owner = '';
	public $files = [];
	
	
	
	
	protected $fillable = [
        'room_id',
        'user_id',
		'message',
		'status',
    ];
	
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
	
	public static function deleteByRoom($roomId=0)
    {
        if (!(int)$roomId) return false;
		$messagesRs = static::select('id', 'room_id')->where('room_id', '=', (int)$roomId)->get();
        foreach ($messagesRs as $messageItem) {
			Storage::deleteDirectory('public/room_chat/'.$messageItem['room_id'].'/'.$messageItem['id']);
            $messageItem->delete();
        }
		Storage::deleteDirectory('public/room_chat/'.$roomId);
		return true;
    }
	
    public static function formateData($data)
    {
		
		$user = Auth::user();
		$userId = (int)$user['id'];

		
		$data->transform(function($entry, $key) use ($userId) {
			
			$files = Storage::files('public/room_chat/'.$entry['room_id'].'/'.$entry['id']);
			$fileUrls = [];
			foreach($files as $file) {
				$mime = Storage::mimeType($file);
				$type = 'file';

				if (in_array($mime, ['image/jpeg', 'image/png'])) $type = 'image';
				
				$fileUrls[] = [
				    'src' => Storage::url($file),
					'name' => basename($file),
					'type' => $type,
				];
			}

			 $entry['name'] = $entry['user_name'];
			 $entry['image'] = User::_getPhoto((int)$entry['user_id']) ?: '/images/default-avatar.jpg';
			 $entry['owner'] = ((int)$entry['user_id'] == $userId)? 'Y' : 'N';
			 $entry['files'] = $fileUrls;

            return $entry;
        });
		return $data;
		
	}
	
	
	
}
