<x-app-layout>
    <div class="py-20 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500 overflow-hidden relative">
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-purple-600/5 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-[800px] mx-auto px-6 relative z-10">
            <!-- Header Section -->
            <div class="mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600/10 rounded-full text-purple-600 font-black text-[10px] uppercase tracking-[0.2em] mb-6">
                    <span class="material-symbols-rounded text-sm">settings</span>
                    System Configuration
                </div>
                <h1 class="text-5xl font-black text-gray-900 dark:text-white tracking-tighter">Preferences</h1>
                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Adjust your global platform experience.</p>
            </div>

            <!-- Settings Card -->
            <div class="bg-white dark:bg-[#181818] rounded-[4rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden p-12 space-y-12">
                
                <!-- Appearance -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <span class="material-symbols-rounded">palette</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Appearance</h3>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Customize the visual interface</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button class="p-8 rounded-[2rem] border-2 border-purple-600 bg-purple-500/5 text-center transition-all">
                            <span class="material-symbols-rounded text-3xl text-purple-600 mb-4">light_mode</span>
                            <p class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Light Protocol</p>
                        </button>
                        <button class="p-8 rounded-[2rem] border-2 border-transparent bg-gray-50 dark:bg-white/2 text-center hover:border-gray-200 dark:hover:border-white/10 transition-all">
                            <span class="material-symbols-rounded text-3xl text-gray-400 mb-4">dark_mode</span>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dark Protocol</p>
                        </button>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="space-y-8 pt-12 border-t border-gray-50 dark:border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <span class="material-symbols-rounded">notifications_active</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Alert Synchronicity</h3>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Manage real-time notifications</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-6 bg-gray-50 dark:bg-white/2 rounded-3xl">
                            <div>
                                <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tight">Push Protocol</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">System wide alerts</p>
                            </div>
                            <div class="w-14 h-8 bg-purple-600 rounded-full relative p-1 cursor-pointer">
                                <div class="w-6 h-6 bg-white rounded-full absolute right-1"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-6 bg-gray-50 dark:bg-white/2 rounded-3xl">
                            <div>
                                <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tight">Email Dispatch</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Critical account updates</p>
                            </div>
                            <div class="w-14 h-8 bg-gray-200 dark:bg-white/10 rounded-full relative p-1 cursor-pointer">
                                <div class="w-6 h-6 bg-white rounded-full absolute left-1 shadow-md"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Language -->
                <div class="space-y-8 pt-12 border-t border-gray-50 dark:border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <span class="material-symbols-rounded">language</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Linguistic Logic</h3>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Platform interface language</p>
                        </div>
                    </div>

                    <x-select name="language" label="Primary Dialect" required="true" icon="translate" hint="Select your preferred communication protocol.">
                        <option value="en">English (US)</option>
                        <option value="es">Español</option>
                        <option value="fr">Français</option>
                    </x-select>
                </div>

                <div class="pt-8">
                    <button class="w-full h-20 bg-slate-900 dark:bg-white text-white dark:text-black rounded-[2.5rem] font-black text-[11px] uppercase tracking-[0.4em] shadow-2xl active:scale-95 transition-all flex items-center justify-center gap-4">
                        <span class="material-symbols-rounded">save</span>
                        Commit Preferences
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
