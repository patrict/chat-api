<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [
        'author_id', 'recipient_id', 'message'
    ];

    protected $hidden = [];

    protected $dates = [
        'read_at',
    ];

    static function getMessages($recipient_id, $unread_only = false) {
        $where_params = [];
        $where_params[] = ['recipient_id', '=', $recipient_id];
        if ($unread_only) {
            $where_params[] = ['read_at', '=', null];
        }

        $results = Message::where($where_params)
            ->join('users', 'users.id', '=', 'messages.author_id')
            ->select('messages.id', 
                     DB::raw('users.first_name || " " || users.last_name AS author'), 
                     'messages.message', 
                     'messages.created_at', 
                     'messages.read_at'
            )
            ->orderBy('messages.created_at', 'asc')
            ->get();
        return $results;
    }
}