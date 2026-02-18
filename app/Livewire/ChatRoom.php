<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Message;
use App\Events\MessageSent;

class ChatRoom extends Component
{

    public $message;
    public $deleteMessageId = null;

    // protected $listeners = ['refreshChat' => '$refresh'];
    protected $listeners = ['echo:chat,MessageSent' => 'refreshChat', 'message-deleted' => '$refresh'];

     // ✅ Validation rules
    protected $rules = [
        'message' => 'required|string|min:1',
    ];

    public function render()
    {
        return view('livewire.chat-room', [
            'messages' => Message::with('user')->get()
        ]);
    }

    public function sendMessage()
    {
         // ✅ Validate first
        $this->validate();
        
        $msg = Message::create([
            'user_id' => auth()->id(),
            'message' => $this->message,
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        $this->message = '';
    }

    public function testDelete($messageId)
    {
        $message = Message::findOrFail($messageId);

        $this->authorize('delete', $message);

        session()->flash('success', 'Policy Allowed ✅');
    }

    public function confirmDelete($id)
    {
        $this->deleteMessageId = $id;

        $this->dispatch('open-delete-modal');
    }

    public function deleteMessage()
    {
        $message = \App\Models\Message::findOrFail($this->deleteMessageId);

        $this->authorize('delete', $message);

        $message->delete(); // soft delete

        $this->deleteMessageId = null;

        $this->dispatch('message-deleted');
    }


}
