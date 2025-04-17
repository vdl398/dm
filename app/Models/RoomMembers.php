<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

//сущность участников комнаты
class RoomMembers extends Model
{
    use HasFactory;
	
	protected $table = 'room_members';
	
	protected $fillable = [
        'room_id',
        'user_id',
		'code',
		'status',
		'is_owner',
    ];
	
	
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'id', 'room_id');
    }
	
	
	public static function deleteByRoom($roomId=0)
    {
        if (!(int)$roomId) return false;
		$rs = static::select('id', 'room_id')->where('room_id', '=', (int)$roomId)->get();
        foreach ($rs as $item) {
            $item->delete();
        }
		return true;
    }
	
	
}
