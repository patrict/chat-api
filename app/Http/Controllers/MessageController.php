<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MessageController extends Controller 
{
    private $user;

    function __construct() {
        // Make the user information available to the functions
        $this->user = Auth::guard('api')->user();
    }

    // Get all messages for the requesting user
    public function getAllMessages() {
        $messages = Message::getMessages($this->user->id);
        return response()->json($messages);
    }

    // Get all unread messages for the requesting user
    public function getAllUnreadMessages() {
        $messages = Message::getMessages($this->user->id, true);
        return response()->json($messages);
    }

    // Create a new message
    public function create(Request $request) {
        $user_id = $this->user->id;

        // Validate the input
        $validator = Validator::make($request->all(), [
            'recipient_id' => [
                'required',
                'numeric',
                // Make sure the recipient user exists
                Rule::exists('users', 'id'), 
                // Make sure the user is not trying to send a message to themselves
                function ($attribute, $value, $fail) use ($user_id) {
                    if ($value == $user_id) { 
                        $fail('You may not send a message to yourself.');                        
                    }
                },
            ],
            'message' => [
                'required'
            ]
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Set the author
        $data = array_merge(['author_id' => $user_id], $request->all());

        // Save the message
        $message = Message::create($data);

        return response()->json($message, 201);
    }

    // Mark a specified message as read
    public function markAsRead($id) {
        $message = Message::findOrFail($id);

        // Do not allow a user to mark someone elses message as read
        if ($message->recipient_id != $this->user->id) {
            return response()->json('Access denied', 400);
        }

        // Only update the read_at if it has not already been set
        if (empty($message->read_at)) {
            $message->read_at = date('Y-m-d H:i:s');
            $message->update();
        }

        return response()->json($message, 200);
    }
}
