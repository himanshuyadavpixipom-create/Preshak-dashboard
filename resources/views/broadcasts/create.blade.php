<x-app-layout>
    <x-slot name="title">Send Broadcast</x-slot>
    <x-slot name="subtitle">Send a message to an entire group of clients at once.</x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <x-section-card>
            <form action="{{ route('broadcasts.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Group Selection -->
                <div>
                    <label for="group_id" class="block text-sm font-medium text-slate-700 mb-1">Select Group <span class="text-red-500">*</span></label>
                    <select id="group_id" name="group_id" required class="mt-1 block w-full border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-slate-900 sm:text-sm">
                        <option value="">-- Select a Group --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @error('group_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Channels -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Channels to Send <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="channels[]" value="whatsapp" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500">
                            <span class="ml-2 text-sm text-slate-700 font-medium">WhatsApp</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="channels[]" value="sms" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500">
                            <span class="ml-2 text-sm text-slate-700 font-medium">SMS</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="channels[]" value="email" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500">
                            <span class="ml-2 text-sm text-slate-700 font-medium">Email</span>
                        </label>
                    </div>
                    @error('channels') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Subject (for Email) -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-slate-700 mb-1">Subject (For Email Only)</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" placeholder="e.g. Important Update from Preshak"
                           class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm sm:text-sm">
                </div>

                <!-- Message Body -->
                <div>
                    <label for="body" class="block text-sm font-medium text-slate-700 mb-1">Message Body <span class="text-red-500">*</span></label>
                    <p class="text-xs text-slate-500 mb-2">Use <code class="bg-slate-100 px-1 py-0.5 rounded">{name}</code> to dynamically insert the client's name.</p>
                    <textarea name="body" id="body" rows="6" required placeholder="Hello {name}, ..."
                              class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm sm:text-sm">{{ old('body') }}</textarea>
                    @error('body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Action -->
                <div class="pt-4 flex items-center justify-end border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-bold tracking-wide hover:bg-primary-700 focus:ring-2 focus:ring-primary-500/50 transition-colors shadow-sm"
                            onclick="return confirm('Are you sure you want to send this broadcast to the entire group? This action cannot be undone.')">
                        Send Broadcast Now
                    </button>
                </div>
            </form>
        </x-section-card>
    </div>
</x-app-layout>
