<x-app-layout>
    <div class="h-screen bg-[#ece5dd] flex items-center justify-center">

        <div class="w-full max-w-7xl h-[92vh] bg-white 
                    shadow-2xl rounded-lg overflow-hidden 
                    border border-gray-300 flex justify-center">

            {{-- Left Empty Sidebar (future users list) --}}
            {{-- <div class="hidden md:block w-1/3 border-r bg-gray-50"> --}}
            {{-- Future: User list --}}
            {{-- </div> --}}


            {{-- Chat Area Centered --}}
            <div class="w-full lg:w-[70%] flex flex-col border border-gray-200">
                <livewire:chat-room />
            </div>

        </div>

    </div>
</x-app-layout>