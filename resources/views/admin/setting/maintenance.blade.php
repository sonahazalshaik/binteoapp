@extends('admin.layouts.app')

@section('panel')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24" x-data="{ isEnabled: {{ gs('maintenance_mode') ? 'true' : 'false' }} }">
    <!-- Header Section -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Maintenance Mode</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">System Control & Operations</p>
        </div>
    </div>

    <form action="{{ route('admin.maintenance.mode.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: Control & Image -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-orange-500/5 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="space-y-8 relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                    <span class="material-symbols-rounded text-lg">power_settings_new</span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">System Status</h3>
                            </div>
                            
                            <!-- Premium Toggle -->
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="status" class="sr-only peer" x-model="isEnabled">
                                <div class="w-14 h-8 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner group-active:scale-95 transition-transform"></div>
                            </label>
                        </div>

                        <div class="p-6 rounded-2xl border border-dashed transition-colors duration-500"
                             :class="isEnabled ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-rose-500/5 border-rose-500/20'">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all duration-500"
                                     :class="isEnabled ? 'bg-emerald-500 text-white animate-pulse' : 'bg-rose-500 text-white'">
                                    <span class="material-symbols-rounded text-2xl" x-text="isEnabled ? 'engineering' : 'check_circle'"></span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest" :class="isEnabled ? 'text-emerald-600' : 'text-rose-600'">
                                        Mode: <span x-text="isEnabled ? 'Active' : 'Disabled'"></span>
                                    </h4>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 leading-relaxed">
                                        <span x-show="isEnabled">The platform is currently locked for maintenance operations.</span>
                                        <span x-show="!isEnabled">The platform is live and accessible to all users.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphic Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">image</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Cover Image</h3>
                        </div>
                        
                        <x-image-uploader class="w-full" :imagePath="getImage(getFilePath('maintenance') . '/' . @$maintenance->data_values->image, getFileSize('maintenance'))" :size="getFileSize('maintenance')" :required="false" name="image" />
                    </div>
                </div>
            </div>

            <!-- Right Panel: Communication -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-10 shadow-sm relative overflow-hidden h-full flex flex-col">
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="space-y-8 relative z-10 flex-grow">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-500">
                                <span class="material-symbols-rounded text-lg">edit_note</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Client Message</h3>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Maintenance Description</label>
                            <textarea class="ckEditor" name="description">@php echo @$maintenance->data_values->description @endphp</textarea>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 ml-1 opacity-60 text-right">This message will be visible to users during maintenance.</p>
                        </div>
                    </div>

                    <div class="pt-10 relative z-10">
                        <button type="submit" class="w-full h-20 rounded-[2rem] bg-orange-600 text-white text-sm font-black uppercase tracking-widest hover:bg-orange-700 active:scale-95 transition-all flex items-center justify-center gap-4 shadow-2xl shadow-orange-500/30">
                            <span class="material-symbols-rounded text-2xl">save</span>
                            Commit System Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('style')
<style>
    .ck-editor__editable { min-height: 400px !important; border-bottom-left-radius: 1.5rem !important; border-bottom-right-radius: 1.5rem !important; }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) { border-color: rgba(0,0,0,0.1) !important; }
    .dark .ck-editor__editable { background: rgba(255,255,255,0.05) !important; color: white !important; border-color: rgba(255,255,255,0.1) !important; }
    .ck.ck-toolbar { border-top-left-radius: 1.5rem !important; border-top-right-radius: 1.5rem !important; background: transparent !important; }
    .dark .ck.ck-toolbar { background: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }
</style>
@endpush

@push('script-lib')
    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@39.0.1/build/ckeditor.js"></script>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        
        const editors = {};

        function initEditors() {
            document.querySelectorAll('.ckEditor').forEach(el => {
                if ($(el).data('editor')) return;
                ClassicEditor
                    .create(el)
                    .then(editor => {
                        $(el).data('editor', editor);
                        editors[el.getAttribute('name')] = editor;
                    })
                    .catch(error => { console.error(error); });
            });
        }

        initEditors();

        $(document).on('submit', 'form', function() {
            $(this).find('.ckEditor').each(function() {
                const editor = $(this).data('editor');
                if (editor) editor.updateSourceElement();
            });
        });

    })(jQuery);
</script>
@endpush

