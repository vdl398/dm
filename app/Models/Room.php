<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

//сущность комнаты
class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = ['title', 'code', 'status', 'port', 'mode', 'time_start', 'time_end'];

    //проверка сокета, вызыватеся при старте комнаты создателем
    public static function checkSocket($roomCode)
    {
        $room = static::select('id', 'port')->where('code', '=', $roomCode)->first();
        if (!$room || !$room->id) {
            return 0;
        }
        $port = $room->port ?: self::getAvailablePort();
        if ($port) {
            //файл статуса работы скрипта сокета на порту, если не найден то создаем фаил статуса и запускаем скрипт сокета на node.js
            //скрипт node.js после подключения первого клиента (создателя комнаты) будет работать при наличии подключенных клиентов
            //при отключении последнего оставшегося клиента фаил статуса будет удален и завершен скрипт node.js
            $checkFile = config('filesystems.CHECK_SOCKET_DIR').$port.'.json';
            if (!file_exists($checkFile)) {
                file_put_contents($checkFile, json_encode(['act' => 'Y', 'port' => $port, 'connections' => 0]));
                passthru('(nohup node '.config('filesystems.SOCKET_SCRIPT').' '.$port.' &) >> /dev/null 2>&1');
            }
        }
        return $port;
    }

    //получить доступный порт сокета
    public static function getAvailablePort()
    {
        $existPorts = [];
        $port = 0;
        foreach (glob(config('filesystems.CHECK_SOCKET_DIR') . '*.json') as $fileName) {
            $path = config('filesystems.CHECK_SOCKET_DIR') . basename($fileName);
            if (file_exists($path)) {
                $arr = (array)json_decode(file_get_contents($path), true);
                if ((int)$arr['port'] > 0) {
                    $existPorts[] = (int)$arr['port'];
                    //пока сделаем ограничение если есть один или более клиентов (не комнат) на сокете то порт занят
                    //как следствие получим 1 комната = 1 порт
                    if (!$port && isset($arr['connections']) && ((int)$arr['connections'] < 2)) $port = (int)$arr['port'];
                }
            }
        }
        if ($port) return $port;
        //генерим незанятый порт в диапазоне 9000 - 9020
        $availablePorts = array_diff(range(9000, 9020), $existPorts);
        return (!empty($availablePorts))? min($availablePorts) : 0;
    }

    //участники комнаты
    public function members(): HasMany
    {
        return $this->hasMany(RoomMembers::class, 'room_id', 'id');
    }

    //чат комнаты
    public function messages(): HasMany
    {
        return $this->hasMany(RoomMessages::class, 'user_id', 'id');
    }


}
