<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AjaxResource;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomMembers;
use App\Models\RoomMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{

    //создать комнату
    public function create(Request $request){
        $resource = new AjaxResource();
		$param = (array)$request->json()->all();
        $roomResult = Room::create([
            'title' => $param['title'],
			'status' => 'N',
			'mode' => $param['mode'], // режим больше или меньше 5 участников, если более 5 участноков то используется медиа сервер, если меньше то прямое соединене между участниками
        ]);
		if (!(int)$roomResult->id) {
			return $resource->sendError(['room create error']);
		}
		$roomResult->code = md5((string)$roomResult->id).'-'.md5((string)date("Y-m-d H:i:s"));
		$roomResult->save();
		$user = Auth::user();


        /*
         * указываем создателя комнаты
         * создаетль при заходе в комнату запускает websocket сервер на выделеном для комнаты порту,
         * активирует комнату, после чего другие участники могут присоедениться
         */
		$rmResult = RoomMembers::create([
            'room_id' => (int)$roomResult->id,
            'user_id' => (int)$user['id'],
			'status' => 'N',
			'is_owner' => 'Y',
        ]);
		if (!(int)$rmResult->id) {
			return $resource->sendError(['room member create error']);
		}
		$rmResult->code = md5((string)$rmResult->id).'-'.md5((string)date("Y-m-d H:i:s"));
		$rmResult->save();
		return $resource->sendSuccess(['link' => '/rooms/'.$roomResult->code]);
    }

    //удалить комнату
	public function deleteRoom(Request $request){
        $resource = new AjaxResource();
		$param = (array)$request->json()->all();

        $room = Room::select('id')->where('code', '=', $param['code'])->first();
		if (!$room || !(int)$room['id']) {
			return $resource->sendError(['room error']);
		}

        if (!RoomMessages::deleteByRoom((int)$room['id'])) {
            return $resource->sendError(['messages delete error']);
		}

        if (!RoomMembers::deleteByRoom((int)$room['id'])) {
            return $resource->sendError(['members delete error']);
		}
        $room->delete();
        return $resource->sendSuccess();
    }

    //добавить участника
    public function addMember(Request $request){
        $resource = new AjaxResource();
		$param = (array)$request->json()->all();

        $room = Room::select('id', 'title', 'code', 'port')->where('code', '=', $param['room_code'])->first();
        $userId = 0;
		if (!empty($param['email'])) {
		    $user = User::select('id')->where('email', '=', $param['email'])->first();
			$userId = (int)$user->id;
		}
		if ($param['current'] == 'Y') {
			if (Auth::check()) {
			    $userId = (int)Auth::user()->id;
			}
		}
		if (!$room || !(int)$room->id) {
			return $resource->sendError(['room not found']);
		}
		if (!$userId) {
			return $resource->sendError(['user not found']);
		}
		$memberExist = RoomMembers::select('id', 'code', 'status')->where('room_id', '=', (int)$room->id)->where('user_id', '=', $userId)->first();
		if ($memberExist && (int)$memberExist->id) {
			if ($param['active'] == 'Y') {
                if ($memberExist->status == 'Y') {
			        return $resource->sendSuccess(['code' => $memberExist->code, 'port' => (int)$room->port, 'activeExist' => 'Y']);
			    }
				$memberExist->status = 'Y';
				$memberExist->save();
			}
			return $resource->sendSuccess(['code' => $memberExist->code, 'port' => (int)$room->port]);
		}

		$rmResult = RoomMembers::create([
            'room_id' => (int)$room->id,
            'user_id' => $userId,
			'status' => ($param['active'] == 'Y')? 'Y' : 'N',
			'is_owner' => 'N',
        ]);
		if (!(int)$rmResult->id) {
			return $resource->sendError(['room member create error']);
		}
		$rmResult->code = md5((string)$rmResult->id).'-'.md5((string)date("Y-m-d H:i:s"));

		$rmResult->save();
		return $resource->sendSuccess(['code' => $rmResult->code, 'port' => (int)$room->port]);
    }

    //проврека websocket сервера на node.js, для организации сеанса webrts между участниками и тд., при успехе вернет порт для подключения
	public function checkSocket(Request $request)
    {
        $resource = new AjaxResource();
		$param = (array)$request->all();

		$room = Room::select('id', 'title', 'code', 'port')->where('code', '=', $param['room_code'])->first();
		if (!$room || !(int)$room->id) {
		    return $resource->sendSuccess(['port' => 0]);
	    }

	    //эту часть выполняет создатель при открытии комнаты
		if (!empty($param['start']) && ($param['start'] == 'Y')) {
			$user = Auth::user();
		    $memberExist = RoomMembers::select('id', 'code', 'is_owner')->where('room_id', '=', (int)$room->id)->where('user_id', '=', $user->id)->first();
		    if ($memberExist && (int)$memberExist->id && ($memberExist->is_owner == 'Y')) {
			    $room->port = Room::checkSocket($param['room_code']); //запускает сокет сервер если не запущен, и возвращает порт
				$room->status = 'Y';
			    $room->save();
		    }
		}
		return $resource->sendSuccess(['port' => (int)$room->port]);
	}

    //удалить участника
	public function deleteMember(Request $request){
        $resource = new AjaxResource();
		$param = (array)$request->all();
		if (empty($param['code'])) {
            return $resource->sendSuccess();
		}
		$memberExist = RoomMembers::select('id', 'code', 'is_owner')->where('code', '=', $param['code'])->first();
		if ($memberExist && (int)$memberExist->id) {
			if ($memberExist->is_owner == 'Y') {
				$memberExist->status = 'N';
				$memberExist->save();
			}
			else {
			    $memberExist->delete();
			}
		}
        return $resource->sendSuccess();
	}

    //список комнат
	public function getList(Request $request){
		$resource = new AjaxResource();
        $param = (array)$request->all();
		if (empty($param)) {
            return $resource->sendSuccess(['list' => []]);
		}
		$user = Auth::user();
        $query = Room::select('id', 'title', 'code', 'status');
		if (!empty($param['owner'])) {	
		    if (!Auth::check()) {
                return $resource->sendSuccess(['list' => []]);
			}
            if ($param['owner'] == 'Y') {
                $query->whereHas('members', function (Builder $q) use ($user) {
                    $q->where('user_id', '=', (int)$user['id'])->where('is_owner', '=', 'Y');
                });
			}
			else {
			    $query->whereDoesntHave('members', function (Builder $q) use ($user) {
                    $q->where('user_id', '=', (int)$user['id'])->where('is_owner', '=', 'Y');
                });
			}
		}
		$rooms = $query->limit(20)->get();
		$rooms->transform(function($entry, $key) {
            $entry['image'] = '/images/play.png';
            return $entry;
        });
        return $resource->sendSuccess(['list' => $rooms->toArray()]);
    }

    //полчить детальные данные комнаты
	public function getItem(Request $request){
		$resource = new AjaxResource();
        $param = (array)$request->all();
        $item = [];
		if (empty($param)) {
            return $resource->sendSuccess(['item' => $item]);
		}

		//получить данные комнаты
        $query = Room::select('id', 'title', 'code', 'mode');
		$user = Auth::user();
		$userId = (int)$user['id'];
		//получить свои коннектор
		$query->addSelect(['connector' => RoomMembers::select('code')->whereColumn('room_id', 'rooms.id')->where('user_id', '=', $userId)->limit(1)]);

        $query->where('code', '=', $param['code']);
        $item = $query->first();

        $user = User::select('name')->where('id', '=', $userId)->first();

        //получить коннекторы текущих участников
		$remoteRs = RoomMembers::select('code')
		    ->addSelect(['user_name' => User::select('name')->whereColumn('id', 'room_members.user_id')->limit(1)])
		    ->where('room_id', '=', (int)$item['id'])->where('user_id', '!=', $userId)->where('status', '=', 'Y')->get();

        return $resource->sendSuccess(['item' => $item, 'remoteConnectors' => $remoteRs->toArray(), 'user_name' => $user->name]);
    }


    //добавить сообщение в чат
    public function addMessage(Request $request){
        $resource = new AjaxResource();
		$param = (array)$request->all();

        $query = Room::select('id');
        $query->where('code', '=', $param['room_code']);
        $room = $query->first();
		if (!$room || !(int)$room['id']) {
			return $resource->sendError(['room error']);
		}
		$user = Auth::user();
		$addresult = RoomMessages::create([
            'room_id' => (int)$room['id'],
            'user_id' => (int)$user['id'],
			'message' => $param['message'],
			'status' => 'N',
        ]);
		if (!(int)$addresult->id) {
			return $resource->sendError(['create error']);
		}
		if (!empty($request->file('file'))) {
			foreach($request->file('file') as $file) {
		       $file->store('public/room_chat/'.$room['id'].'/'.$addresult->id);
			}
		}
        return $resource->sendSuccess();
    }

    //получить сообщения чата
	public function getMessages(Request $request){
		$resource = new AjaxResource();
        $param = (array)$request->all();
        $item = [];
		if (empty($param)) {
            return $resource->sendSuccess(['messages' => []]);
		}
		$user = Auth::user();
        $query = Room::select('id');
        $query->where('code', '=', $param['room_code']);
        $item = $query->first();
		$data = RoomMessages::select('id', 'user_id', 'room_id', 'message')->addSelect(['user_name' => User::select('name')->whereColumn('id', 'room_messages.user_id')->limit(1)])->where('room_id', '=', (int)$item['id'])->get();

        return $resource->sendSuccess(['messages' => RoomMessages::formateData($data)->toArray()]);
    }



}
