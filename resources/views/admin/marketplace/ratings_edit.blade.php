@extends('admin.layouts.app')

@section('title', 'Edit Rating')
@section('header_title', 'Edit Rating')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-white/10 flex justify-between items-center bg-slate-50 dark:bg-white/5">
            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-wider">Edit Rating Entry</h3>
            <a href="{{ route('admin.marketplace.ratings.index') }}" class="text-slate-400 hover:text-indigo-500 transition-colors flex items-center gap-1 text-[10px] font-black uppercase tracking-widest">
                <span class="material-symbols-rounded text-sm">arrow_back</span>
                Back
            </a>
        </div>
        <form action="{{ route('admin.marketplace.ratings.update', $rating->id) }}" method="POST" class="p-8">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Select Talent (Reviewed)</label>
                    <select name="marketplace_id" required class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Choose Talent --</option>
                        @foreach($talents as $talent)
                            <option value="{{ $talent->id }}" {{ $rating->marketplace_id == $talent->id ? 'selected' : '' }}>
                                {{ $talent->name }} ({{ $talent->type }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Rating (1-5 Stars)</label>
                    <input type="number" name="rating" min="1" max="5" value="{{ $rating->rating }}" required class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-4 border-t border-slate-100 dark:border-white/10 pt-6">
                <button type="submit" class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-white bg-indigo-500 hover:bg-indigo-600 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">Update Rating</button>
            </div>
        </form>
    </div>
</div>
@endsection
