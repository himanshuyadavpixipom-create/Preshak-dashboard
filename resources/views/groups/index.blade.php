<x-app-layout>
    <x-slot name="title">Client Groups</x-slot>
    <x-slot name="subtitle">Organize your clients into groups for targeted messaging.</x-slot>

    <!-- Top Action Bar -->
    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ route('groups.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500/50 transition-all shadow-sm shrink-0">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create Group
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <x-section-card>
        @if($groups->isEmpty())
            <x-empty-state 
                title="No groups found" 
                description="Get started by creating a new group to categorize clients."
            >
                <x-slot name="icon">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </x-slot>
                <x-slot name="action">
                    <a href="{{ route('groups.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-primary-700 transition-all shadow-sm">
                        Create Your First Group
                    </a>
                </x-slot>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-xs font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-4 rounded-tl-xl rounded-bl-xl">Name</th>
                            <th scope="col" class="px-6 py-4">Description</th>
                            <th scope="col" class="px-6 py-4">Members</th>
                            <th scope="col" class="px-6 py-4 rounded-tr-xl rounded-br-xl text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($groups as $group)
                            <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $group->name }}</td>
                                <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $group->description ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $group->clients_count }} clients
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('groups.edit', $group) }}" class="text-slate-400 hover:text-primary-600 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('groups.destroy', $group) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this group? Clients will not be deleted.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-section-card>
</x-app-layout>
