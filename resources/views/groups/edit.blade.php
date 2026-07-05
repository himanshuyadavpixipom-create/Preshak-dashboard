<x-app-layout>
    <x-slot name="title">Edit Group</x-slot>
    <x-slot name="subtitle">Update details for the {{ $group->name }} group.</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('groups.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Groups
            </a>
        </div>

        <x-section-card>
            <form action="{{ route('groups.update', $group) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Group Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $group->name) }}" required 
                           class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm sm:text-sm">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm sm:text-sm">{{ old('description', $group->description) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Optional. Briefly describe the purpose of this group.</p>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end border-t border-slate-100 gap-4">
                    <a href="{{ route('groups.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700 focus:ring-2 focus:ring-primary-500/50 transition-colors shadow-sm">
                        Update Group
                    </button>
                </div>
            </form>
        </x-section-card>
    </div>
</x-app-layout>
