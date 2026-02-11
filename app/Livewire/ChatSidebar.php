<?php

namespace App\Livewire;

use Livewire\Component;

class ChatSidebar extends Component
{
    public function render()
    {
        return view('livewire.chat-sidebar', [
            'users' => User::where('id', '!=', auth()->id())->get()
        ]);
    }
}
