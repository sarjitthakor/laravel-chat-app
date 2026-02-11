<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Message;
use App\Events\MessageSent;

class ChatRoom extends Component
{

    public $message;

    // protected $listeners = ['refreshChat' => '$refresh'];
    protected $listeners = ['echo:chat,MessageSent' => 'refreshChat'];

    public function render()
    {
        return view('livewire.chat-room', [
            'messages' => Message::with('user')->get()
        ]);
    }

    public function sendMessage()
    {
        $msg = Message::create([
            'user_id' => auth()->id(),
            'message' => $this->message,
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        $this->message = '';
    }
}
