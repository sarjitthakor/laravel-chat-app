<div class="flex flex-col h-full">

    {{-- HEADER --}}
    <div class="bg-[#075E54] text-white px-6 py-3 flex items-center justify-between shadow-md">

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gray-300"></div>

            <div>
                <div class="font-semibold text-sm">Public Chat</div>
                <div class="text-xs opacity-80">Online</div>
            </div>
        </div>

        <div class="text-sm opacity-80">
            {{ now()->format('d M Y') }}
        </div>

    </div>


    {{-- CHAT BODY --}}
    <div id="chat-container" class="flex-1 overflow-y-auto px-6 py-4 space-y-3"
        style="background-image: url('https://i.imgur.com/8Km9tLL.png'); background-size: cover;">

        @foreach ($messages as $msg)

        @if ($msg->user_id === auth()->id())

        {{-- My Message --}}
        <div class="flex justify-end">

            <div class="bg-[#DCF8C6] text-black px-4 py-2 rounded-2xl rounded-br-md max-w-md shadow relative">

                <div class="text-sm break-words">
                    {{ $msg->message }}
                </div>
                @can('delete', $msg)
                    <button wire:click="confirmDelete({{ $msg->id }})" class="text-xs text-red-500 mt-1 hover:text-red-700">
                        🗑 Delete
                    </button>
                @endcan

                <div class="text-[11px] text-right mt-1 text-gray-600">
                    {{ $msg->created_at->format('H:i') }}
                </div>

            </div>

        </div>

        @else

        {{-- Other Message --}}
        <div class="flex justify-start">

            <div class="bg-white px-4 py-2 rounded-2xl rounded-bl-md max-w-md shadow relative">

                <div class="text-xs font-semibold text-green-700 mb-1">
                    {{ $msg->user->name }}
                </div>

                <div class="text-sm break-words">
                    {{ $msg->message }}
                </div>

                <div class="text-[11px] text-right mt-1 text-gray-500">
                    {{ $msg->created_at->format('H:i') }}
                </div>

            </div>

        </div>

        @endif

        @endforeach

    </div>


    {{-- INPUT AREA --}}
    <div class="bg-[#F0F2F5] px-4 py-3 flex items-center gap-3 border-t">

        <input wire:model="message" wire:keydown.enter="sendMessage"
            class="flex-1 bg-white rounded-full px-5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm"
            placeholder="Type a message">

        <button wire:click="sendMessage"
            class="bg-[#25D366] text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-green-600 transition shadow">
            Send
        </button>

    </div>

    <div x-data="{ open:false }" x-on:open-delete-modal.window="open=true" x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-black/40">
    
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <p class="mb-4">Delete this message?</p>
    
            <div class="flex gap-3 justify-end">
                <button @click="open=false" class="px-4 py-2 bg-gray-300 rounded">
                    Cancel
                </button>
    
                <button wire:click="deleteMessage" @click="open=false" class="px-4 py-2 bg-red-500 text-white rounded">
                    Delete
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('livewire:load', function () {

    function scrollToBottom() {
        let container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    // Initial load
    scrollToBottom();

    // After Livewire updates
    Livewire.hook('message.processed', () => {
        scrollToBottom();
    });

});
</script>