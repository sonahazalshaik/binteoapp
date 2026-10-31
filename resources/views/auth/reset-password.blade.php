<x-guest-layout>
    <div class="text-center mb-8">
        <!-- Logo -->
        <div class="inline-block mb-4">
            <div class="w-16 h-16 bg-white dark:bg-white rounded-2xl flex items-center justify-center p-2 shadow-[0_0_30px_rgba(0,0,0,0.05)] dark:shadow-[0_0_30px_rgba(255,255,255,0.1)]">
                <img src="{{ siteLogo() }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="mt-2 text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.3em] font-['Space_Grotesk']">{{ gs('site_name') }}</div>
        </div>

        <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 uppercase tracking-tight ">Reset Password</h1>
        <p class="text-gray-500 dark:text-[#e6beb2]/60 text-[10px] leading-relaxed px-4 uppercase tracking-widest font-bold">
            {{ __('Almost there! Secure your account with a new password below.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6 w-full" x-data="{ emailFocus: false, passFocus: false, confirmFocus: false, passShow: false, confirmShow: false, loading: false }" @submit="loading = true">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="relative group">
            <div class="absolute top-0 left-0 h-14 flex items-center pl-4 pointer-events-none transition-all duration-300 z-10" 
                 :class="emailFocus ? 'text-[#ff571a]' : 'text-gray-400 dark:text-white/20'">
                <span class="material-symbols-rounded text-[22px]">alternate_email</span>
            </div>
            <input type="email" name="email" id="email" required
                   @focus="emailFocus = true" @blur="emailFocus = false"
                   class="peer w-full h-14 pl-12 pr-4 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-transparent focus:bg-gray-50 dark:focus:bg-white/10 focus:border-[#ff571a] focus:ring-4 focus:ring-[#ff571a]/10 transition-all outline-none"
                   placeholder="Email Address" value="{{ old('email', $request->email) }}" />
            <label for="email" class="absolute left-12 -top-2.5 px-2 text-[9px] font-black uppercase tracking-[0.2em] text-[#ff571a] transition-all peer-placeholder-shown:text-[11px] peer-placeholder-shown:text-gray-400 dark:peer-placeholder-shown:text-white/20 peer-placeholder-shown:top-4 peer-placeholder-shown:bg-transparent peer-focus:-top-2.5 peer-focus:text-[9px] peer-focus:text-[#ff571a] font-['Space_Grotesk'] pointer-events-none">
                Email Address
            </label>
            <x-input-error :messages="$errors->get('email')" class="mt-1 pl-4 text-[9px] text-rose-500 font-bold uppercase tracking-widest" />
        </div>

        <!-- Password -->
        <div class="relative group">
            <div class="absolute top-0 left-0 h-14 flex items-center pl-4 pointer-events-none transition-all duration-300 z-10" 
                 :class="passFocus ? 'text-[#ff571a]' : 'text-gray-400 dark:text-white/20'">
                <span class="material-symbols-rounded text-[22px]">lock</span>
            </div>
            <input :type="passShow ? 'text' : 'password'" name="password" id="password" required autofocus
                   @focus="passFocus = true" @blur="passFocus = false"
                   class="peer w-full h-14 pl-12 pr-12 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-transparent focus:bg-gray-50 dark:focus:bg-white/10 focus:border-[#ff571a] focus:ring-4 focus:ring-[#ff571a]/10 transition-all outline-none"
                   placeholder="New Password" />
            <button type="button" @click="passShow = !passShow" 
                class="absolute right-3 top-7 -translate-y-1/2 text-gray-400 dark:text-white/30 hover:text-[#ff571a] transition-colors z-10">
                <span class="material-symbols-rounded text-lg" x-text="passShow ? 'visibility_off' : 'visibility'"></span>
            </button>
            <label for="password" class="absolute left-12 -top-2.5 px-2 text-[9px] font-black uppercase tracking-[0.2em] text-[#ff571a] transition-all peer-placeholder-shown:text-[11px] peer-placeholder-shown:text-gray-400 dark:peer-placeholder-shown:text-white/20 peer-placeholder-shown:top-4 peer-placeholder-shown:bg-transparent peer-focus:-top-2.5 peer-focus:text-[9px] peer-focus:text-[#ff571a] font-['Space_Grotesk'] pointer-events-none">
                New Password
            </label>
            <x-input-error :messages="$errors->get('password')" class="mt-1 pl-4 text-[9px] text-rose-500 font-bold uppercase tracking-widest" />
        </div>

        <!-- Confirm Password -->
        <div class="relative group">
            <div class="absolute top-0 left-0 h-14 flex items-center pl-4 pointer-events-none transition-all duration-300 z-10" 
                 :class="confirmFocus ? 'text-[#ff571a]' : 'text-gray-400 dark:text-white/20'">
                <span class="material-symbols-rounded text-[22px]">verified_user</span>
            </div>
            <input :type="confirmShow ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                   @focus="confirmFocus = true" @blur="confirmFocus = false"
                   class="peer w-full h-14 pl-12 pr-12 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-transparent focus:bg-gray-50 dark:focus:bg-white/10 focus:border-[#ff571a] focus:ring-4 focus:ring-[#ff571a]/10 transition-all outline-none"
                   placeholder="Confirm Password" />
            <button type="button" @click="confirmShow = !confirmShow" 
                class="absolute right-3 top-7 -translate-y-1/2 text-gray-400 dark:text-white/30 hover:text-[#ff571a] transition-colors z-10">
                <span class="material-symbols-rounded text-lg" x-text="confirmShow ? 'visibility_off' : 'visibility'"></span>
            </button>
            <label for="password_confirmation" class="absolute left-12 -top-2.5 px-2 text-[9px] font-black uppercase tracking-[0.2em] text-[#ff571a] transition-all peer-placeholder-shown:text-[11px] peer-placeholder-shown:text-gray-400 dark:peer-placeholder-shown:text-white/20 peer-placeholder-shown:top-4 peer-placeholder-shown:bg-transparent peer-focus:-top-2.5 peer-focus:text-[9px] peer-focus:text-[#ff571a] font-['Space_Grotesk'] pointer-events-none">
                Confirm Password
            </label>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 pl-4 text-[9px] text-rose-500 font-bold uppercase tracking-widest" />
        </div>

        <div class="pt-2">
            <button type="submit" :disabled="loading"
                class="relative w-full h-14 flex items-center justify-center bg-gradient-to-r from-[#ff571a] to-[#ae3200] overflow-hidden rounded-xl text-white font-black text-[13px] uppercase tracking-[0.2em] shadow-[0_10px_40px_rgba(255,87,26,0.4)] hover:shadow-[0_15px_50px_rgba(255,87,26,0.6)] active:scale-[0.98] transition-all group disabled:opacity-60 disabled:cursor-not-allowed disabled:active:scale-100">
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                <template x-if="!loading">
                    <span>Reset Password</span>
                </template>
                <template x-if="loading">
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Resetting...
                    </span>
                </template>
            </button>
        </div>
    </form>
</x-guest-layout>

