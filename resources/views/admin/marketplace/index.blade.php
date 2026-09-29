@extends('admin.layouts.app')

@section('panel')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { 
            cb.checked = this.selectAll; 
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }
}" class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700 pb-24">
    <!-- Main Container -->
    <div class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm">
        @include('admin.components.header-toolbar', [
            'title' => 'Creator Marketplace',
            'items' => $categories,
            'createRoute' => route('admin.marketplace.create'),
            'createLabel' => 'Add'
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar', [
                'module' => 'marketplace',
                'showBulkActions' => true,
                'bulkRoute' => route('admin.marketplace.bulk'),
                'bulkActions' => ['featured', 'unfeatured', 'delete'],
                'exportTotal' => count($categories)
            ])
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="selectAllHeader" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                        </th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Talent Info</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Location</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Performance</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($categories as $talent)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/2 transition-colors">
                        <td class="px-8 py-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $talent->id }}">
                            </label>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-black/20 overflow-hidden border border-slate-200 dark:border-white/10 group-hover:border-orange-500/30 transition-all">
                                    @if($talent->image)
                                        <img src="{{ $talent->photoUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-slate-200 to-slate-300 dark:from-white/5 dark:to-white/10">
                                            <span class="text-lg font-black text-slate-400 dark:text-white/20 ">{{ $talent->getInitials() }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-[13px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $talent->name }}</h5>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-white/30 mt-1.5 flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-blue-500/10 text-blue-500 rounded text-[9px] uppercase tracking-widest">{{ $talent->type }}</span>
                                        @if($talent->business_type)
                                        <span class="px-2 py-0.5 bg-purple-500/10 text-purple-500 rounded text-[9px] uppercase tracking-widest">{{ $talent->business_type }}</span>
                                        @endif
                                        • {{ $talent->email }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-[11px] font-black text-slate-700 dark:text-white/70 ">
                                    <span class="material-symbols-rounded text-[14px]">location_on</span>
                                    {{ $talent->location ?? 'Not Specified' }}
                                </div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    Registered {{ $talent->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-1">
                                        <span class="text-[13px] font-black text-slate-900 dark:text-white">{{ $talent->rating }}</span>
                                        <span class="material-symbols-rounded text-amber-500 text-[14px] fill-1">star</span>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $talent->projects_count }} Projects</span>
                                </div>
                                @if($talent->is_featured)
                                <div class="px-3 py-1.5 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-500 text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 shadow-lg shadow-purple-500/5">
                                    <span class="material-symbols-rounded text-[12px] fill-1">verified</span>
                                    Featured
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer toggle-status" data-id="{{ $talent->id }}" {{ $talent->status ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <form action="{{ route('admin.marketplace.toggle-featured', $talent->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="h-10 px-3 rounded-xl {{ $talent->is_featured ? 'bg-amber-500 text-white' : 'bg-slate-100 dark:bg-white/5 text-slate-400' }} hover:scale-105 transition-all flex items-center gap-2 shadow-sm" title="{{ $talent->is_featured ? 'Unfeature Creator' : 'Feature Creator' }}">
                                        <span class="material-symbols-rounded text-[18px] {{ $talent->is_featured ? 'fill-1' : '' }}">star</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter">{{ $talent->is_featured ? 'Unfeature' : 'Feature' }}</span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.marketplace.show', $talent->id) }}" class="h-10 px-3 rounded-xl bg-orange-500/10 text-orange-500 hover:bg-orange-500 hover:text-white transition-all flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-rounded text-[18px]">visibility</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">View</span>
                                </a>
                                <a href="{{ route('admin.marketplace.login.as.talent', $talent->id) }}" class="h-10 px-3 rounded-xl bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-rounded text-[18px]">login</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Login</span>
                                </a>
                                <a href="{{ route('admin.marketplace.edit', $talent->id) }}" class="h-10 px-3 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 hover:bg-orange-500 hover:text-white transition-all flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-rounded text-[18px]">edit_square</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Edit</span>
                                </a>
                                <button type="button" 
                                        @click="window.adminSwal({
                                            title: 'Terminate Creator?',
                                            text: 'This action will permanently purge this profile from the marketplace directory.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            confirmButtonText: 'Confirm Purge',
                                            cancelButtonText: 'Abort'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('delete-form-{{ $talent->id }}').submit();
                                            }
                                        })"
                                        class="h-10 px-3 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-rounded text-[18px]">delete</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Delete</span>
                                </button>
                                <form id="delete-form-{{ $talent->id }}" action="{{ route('admin.marketplace.destroy', $talent->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-8 py-20 text-center text-muted">@lang('No creators detected')</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View (Grid Mode) -->
        <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 pt-6">
                @forelse($categories as $talent)
                <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-orange-500 to-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $talent->id }}">
                            </label>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.marketplace.toggle-featured', $talent->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-10 h-10 rounded-xl {{ $talent->is_featured ? 'bg-amber-500 text-white' : 'bg-slate-50 dark:bg-white/5 text-slate-400' }} border border-slate-200 dark:border-white/10 flex items-center justify-center hover:scale-110 transition-all shadow-sm">
                                        <span class="material-symbols-rounded text-lg {{ $talent->is_featured ? 'fill-1' : '' }}">star</span>
                                    </button>
                                </form>
                                @if($talent->number)
                                <a href="tel:{{ $talent->number }}" class="w-10 h-10 rounded-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-all">
                                    <span class="material-symbols-rounded text-lg">call</span>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                            <div class="relative flex-shrink-0">
                                <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-slate-200 to-slate-100 dark:from-white/10 dark:to-white/5 shadow-inner">
                                    <div class="w-full h-full rounded-full overflow-hidden border-4 border-white dark:border-[#1a1a1a] shadow-xl">
                                        @if($talent->image)
                                            <img src="{{ $talent->photoUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 font-black text-2xl ">
                                                {{ $talent->getInitials() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow min-w-0 w-full">
                                <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter mb-1">{{ $talent->name }}</h4>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-4">
                                    <span class="px-3 py-1 rounded-lg bg-blue-500/10 text-blue-500 text-[9px] font-black uppercase tracking-widest">{{ $talent->type }}</span>
                                    @if($talent->business_type)
                                        <span class="px-3 py-1 rounded-lg bg-purple-500/10 text-purple-500 text-[9px] font-black uppercase tracking-widest">{{ $talent->business_type }}</span>
                                    @endif
                                    @if($talent->status)
                                        <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">Active</span>
                                    @else
                                        <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">Inactive</span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-left">
                                    <div>
                                        <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Rating</span>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[11px] font-black dark:text-white">{{ $talent->rating }}</span>
                                            <span class="material-symbols-rounded text-amber-500 text-[14px] fill-1">star</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Projects</span>
                                        <span class="text-[11px] font-black dark:text-white uppercase ">{{ $talent->projects_count }} Items</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Location</span>
                                        <span class="text-[11px] font-black dark:text-white uppercase truncate block">{{ $talent->location ?? 'Not Specified' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 grid grid-cols-2 gap-3">
                            <form action="{{ route('admin.marketplace.toggle-featured', $talent->id) }}" method="POST" class="flex">
                                @csrf
                                <button type="submit" class="flex-1 h-12 rounded-xl {{ $talent->is_featured ? 'bg-amber-500 text-white' : 'bg-slate-50 dark:bg-white/5 text-slate-400' }} border border-slate-200 dark:border-white/10 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:scale-105 transition-all ">
                                    <span class="material-symbols-rounded text-[16px] {{ $talent->is_featured ? 'fill-1' : '' }}">star</span> 
                                    <span>{{ $talent->is_featured ? 'Unfeature' : 'Feature' }}</span>
                                </button>
                            </form>
                            <a href="{{ route('admin.marketplace.show', $talent->id) }}" class="h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-orange-500 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:bg-orange-500 hover:text-white transition-all ">
                                <span class="material-symbols-rounded text-[16px]">visibility</span> <span>View</span>
                            </a>
                            <a href="{{ route('admin.marketplace.login.as.talent', $talent->id) }}" class="h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-blue-500 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:bg-blue-500 hover:text-white transition-all ">
                                <span class="material-symbols-rounded text-[16px]">login</span> <span>Login</span>
                            </a>
                            <a href="{{ route('admin.marketplace.edit', $talent->id) }}" class="h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/40 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:bg-orange-500 hover:text-white transition-all ">
                                <span class="material-symbols-rounded text-[16px]">edit</span> <span>Edit</span>
                            </a>
                            <button type="button" 
                                    @click="window.adminSwal({
                                        title: 'Terminate Creator?',
                                        text: 'This action will permanently purge this profile from the marketplace directory.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#ef4444',
                                        confirmButtonText: 'Confirm Purge',
                                        cancelButtonText: 'Abort'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('delete-form-app-{{ $talent->id }}').submit();
                                        }
                                    })"
                                    class="h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-rose-500 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:bg-rose-500 hover:text-white transition-all ">
                                <span class="material-symbols-rounded text-[16px]">delete</span> <span>Delete</span>
                            </button>
                            <form action="{{ route('admin.marketplace.status', $talent->id) }}" method="POST" class="flex col-span-2">
                                @csrf
                                <button type="submit" class="flex-1 h-12 rounded-xl border-2 {{ $talent->status ? 'border-rose-500/30 text-rose-500' : 'border-emerald-500/30 text-emerald-500' }} flex flex-row items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest transition-all ">
                                    <span class="material-symbols-rounded text-[18px]">{{ $talent->status ? 'block' : 'check_circle' }}</span> 
                                    <span>{{ $talent->status ? 'Disable Account' : 'Enable Account' }}</span>
                                </button>
                            </form>
                        </div>
                        <form id="delete-form-app-{{ $talent->id }}" action="{{ route('admin.marketplace.destroy', $talent->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                    <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">person_off</span>
                    <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">No creators found matching your search</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $categories->links() }}
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $(function() {
        $('.toggle-status').on('change', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `{{ route('admin.marketplace.status', ['id' => ':id']) }}`.replace(':id', id),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    notify('success', response.message);
                }
            });
        });
    });
</script>
@endpush

