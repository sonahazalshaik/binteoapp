@extends('admin.layouts.app')

@section('title', 'Security & Announcements')
@section('header_title', 'System Security')

@section('content')
<div class="px-4 lg:px-0 grid grid-cols-1 lg:grid-cols-3 gap-4 mb-12 animate-in fade-in slide-in-from-bottom-10 duration-1000">
    <!-- Announcements Command -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group transition-all duration-500">
            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-6 flex items-center gap-3">
                <span class="material-symbols-rounded text-orange-500">broadcast_on_home</span>
                Send Announcement
            </h3>
            
            <form action="{{ route('admin.security.announcements.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Announcement Title</label>
                    <input type="text" name="title" placeholder="Enter announcement title..." class="w-full bg-white/40 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-[12px] font-bold text-slate-900 dark:text-white outline-none" required>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Announcement Message</label>
                    <textarea name="message" rows="4" placeholder="Enter what users should see..." class="w-full bg-white/40 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-[12px] font-bold text-slate-900 dark:text-white outline-none resize-none" required></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl orange-gradient-primary" style="background: linear-gradient(90deg, #ff8a00 0%, #ff5200 50%, #e52e71 100%) !important; dark:bg-white text-white dark:text-black text-[9px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl mt-4">Post Announcement</button>
            </form>
        </div>

        <!-- Banned Personnel -->
        <div class="bg-gradient-to-tr from-rose-600 to-red-500 p-4 rounded-xl shadow-2xl relative overflow-hidden group">
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-3xl"></div>
            <p class="text-[9px] font-black text-white/60 uppercase tracking-widest">Total Banned Users</p>
            <div class="flex items-baseline gap-2 mt-2">
                <h2 class="text-5xl font-black text-white tracking-tighter">{{ $bannedUsers }}</h2>
                <span class="text-white/40 font-black text-[9px] uppercase tracking-widest">Accounts Restricted</span>
            </div>
            <div class="mt-8">
                <a href="{{ route('admin.users.all') }}" class="inline-flex items-center gap-2 h-10 px-6 rounded-full bg-white text-rose-600 text-[9px] font-black uppercase tracking-widest hover:scale-110 transition-all shadow-xl">
                    Manage Users <span class="material-symbols-rounded text-base">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- IP Blocking Operations -->
        <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group transition-all duration-500">
            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-6 flex items-center gap-3">
                <span class="material-symbols-rounded text-rose-500">block</span>
                Block IP Address
            </h3>
            
            <form action="{{ route('admin.security.ip.block') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">IP Address</label>
                    <input type="text" name="ip_address" placeholder="127.0.0.1" class="w-full bg-white/40 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-[12px] font-bold text-slate-900 dark:text-white outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Reason (Optional)</label>
                    <input type="text" name="reason" placeholder="Suspicious activity" class="w-full bg-white/40 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-[12px] font-bold text-slate-900 dark:text-white outline-none">
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl mt-4">Execute Block</button>
            </form>

            @if(count($blockedIps) > 0)
            <div class="mt-8 space-y-3">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Recently Blocked</p>
                @foreach($blockedIps as $ip)
                <div class="flex items-center justify-between p-3 bg-white/40 dark:bg-white/5 rounded-lg border border-slate-100 dark:border-white/10 group">
                    <div>
                        <p class="text-[10px] font-black text-slate-900 dark:text-white">{{ $ip->ip_address }}</p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $ip->reason ?? 'No reason' }}</p>
                    </div>
                    <form action="{{ route('admin.security.ip.unblock', $ip->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-500 hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-sm">delete</span>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Activity Log Fleet -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-white dark:border-white/5 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-500">
            <div class="px-10 py-10 border-b border-white/40 dark:border-white/5">
                <h2 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase">Activity Logs</h2>
                <p class="text-[9px] font-bold text-slate-500 dark:text-white/30 uppercase tracking-widest mt-2 leading-relaxed ">Logs of all user and administrative actions</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-100/50 dark:bg-white/5">
                            <th class="px-6 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500">User</th>
                            <th class="px-6 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500">Action</th>
                            <th class="px-6 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500">IP & Browser</th>
                            <th class="px-6 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500 text-right">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/40 dark:divide-white/5">
                        @forelse($logs as $log)
                        <tr class="hover:bg-white/40 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-2.5 text-[9px] font-black uppercase tracking-tighter text-slate-400">
                                <div class="flex items-center gap-4">
                                     <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center font-black text-slate-900 dark:text-white border border-white dark:border-white/10 transition-transform group-hover:scale-110">
                                        {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-900 dark:text-white tracking-tight truncate">{{ $log->user ? $log->user->username : 'System' }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest mt-1">{{ $log->user ? $log->user->email : 'Automatic Task' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-2.5">
                                <span class="px-3 py-1 rounded-xl bg-blue-500/10 text-blue-600 text-[9px] font-black uppercase tracking-widest border border-blue-500/10 shadow-sm">{{ $log->action }}</span>
                                <p class="text-[9px] font-medium text-slate-400 mt-2 line-clamp-1 max-w-xs">{{ $log->description }}</p>
                            </td>
                            <td class="px-6 py-2.5">
                                <div class="space-y-1">
                                    <p class="text-[9px] font-black text-slate-900 dark:text-white tracking-widest uppercase">{{ $log->ip_address }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 truncate max-w-[150px] ">{{ $log->user_agent }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-2.5 text-right">
                                <span class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ">{{ $log->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-xs opacity-20">No activity logs found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
                <div class="px-6 py-2.5 border-t border-white/40 dark:border-white/5 bg-white/20 dark:bg-white/[0.01]">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        <!-- Active Broadcasts -->
        <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/5 rounded-[2rem] p-6 shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">Current Announcements</h3>
                <span class="px-5 py-2 flex items-center bg-emerald-500 text-white rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20">Active Now</span>
            </div>
            
            <div class="space-y-6">
                @foreach($announcements as $announcement)
                <div class="flex items-center justify-between p-6 bg-white/40 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/10 group animate-in slide-in-from-right-10 duration-500">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-10 rounded-xl orange-gradient-primary" style="background: linear-gradient(90deg, #ff8a00 0%, #ff5200 50%, #e52e71 100%) !important; flex items-center justify-center text-white shadow-xl transition-transform group-hover:scale-110">
                            <span class="material-symbols-rounded">notifications_active</span>
                        </div>
                        <div>
                            <h4 class="text-[11px] font-black text-slate-900 dark:text-white tracking-tight uppercase">{{ $announcement->title }}</h4>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-[9px] font-black uppercase tracking-widest {{ $announcement->is_active ? 'text-emerald-500' : 'text-slate-400' }}">{{ $announcement->is_active ? 'Active' : 'Inactive' }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-white/10"></span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Created: {{ $announcement->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3  transition-all">
                        <form action="{{ route('admin.security.announcements.toggle', $announcement->id) }}" method="POST">
                            @csrf
                            <button class="w-11 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-500 transition-all active:scale-90 shadow-sm" title="Toggle Visibility">
                                <span class="material-symbols-rounded">{{ $announcement->is_active ? 'visibility_off' : 'visibility' }}</span>
                            </button>
                        </form>
                        <form action="{{ route('admin.security.announcements.destroy', $announcement->id) }}" method="POST" class="delete-announcement-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="w-11 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all active:scale-90 shadow-sm delete-btn" title="Delete Permanent">
                                <span class="material-symbols-rounded">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('.delete-btn').on('click', function() {
        const form = $(this).closest('form');
        window.adminSwal({
            title: 'Are you sure?',
            text: 'Immediately delete this announcement?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F97316',
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush









