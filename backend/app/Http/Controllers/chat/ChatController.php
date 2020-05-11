<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Events\MyEvent;
use DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class ChatController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function karyawan()
    {   
        $karyawan = Karyawan::where('user_id', $this->user()->id)->first();
        return $karyawan;
    }

    public function userList()
    {
        $users = User::select('id','name','avatar','isLogin')->withCount('messages')->get();
        return $users;
    }

    public function chatWith($user)
    {
        $user = User::findOrFail($user);
        $query = Message::orderBy('created_at', 'asc');

        $query->where(function($query) use ($user) {
            $query->where([['from', '=', $this->user()->id], ['to', '=', $user->id]])
                ->orWhere([['to', '=', $this->user()->id], ['from', '=', $user->id]]);
        });

        $data = $query->get();

        // update messages status to read
        $messages = Message::where([['to', '=', $this->user()->id], ['from', '=', $user->id]])->get();
        foreach ($messages as $message) {
            $message->read = '1';
            $message->update();
        }

        return $data;

        // $messages = DB::table('messages')->orderBy('created_at', 'asc')->where([
        //     ['from', '=', $this->user()->id],
        //     ['to', '=', $user->id],
        // ])->orWhere([
        //     ['from', '=', $user->id],
        //     ['to', '=', $this->user()->id],
        // ])->get();

        // return $messages;
    }

    public function chatSend(Request $request)
    {
        $this->validate($request, [
            'to' => 'required',
            'message' => 'required|string|max:600',
        ]);

        $user = User::findOrFail($request->to);

        $message = new Message;
        $message->from = $this->user()->id;
        $message->to = $user->id;
        $message->message = $request->message;
        
        if ($message->save()) {
            event(new MyEvent($message));
            return $message;
        }
    }
}
