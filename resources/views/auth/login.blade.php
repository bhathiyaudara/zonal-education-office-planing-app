@extends('layouts.base')

@section('title', 'Login')

@section('body')
<div class="min-h-screen flex">
    {{-- Left Panel - Decorative --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>

        <div class="relative z-10 flex flex-col justify-center items-center text-center p-12 text-white w-full">
            <div class="mb-8 animate-float">
                <img src="{{ asset('images/logo.png') }}" alt="Wilgamuwa Zonal Education Office" class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 shadow-2xl max-w-[300px]">
            </div>

            <p class="text-sm text-blue-200/80 max-w-sm">
                Wilgamuwa Education Zone - Streamlined Teacher & School Management
            </p>

            <div class="mt-12 space-y-4 text-left">
                @foreach(['Manage Schools & Teachers', 'Manage Student Demographics', 'Generate Reports & Analytics'] as $feature)
                <div class="flex items-center gap-3 text-blue-100">
                    <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                        <span class="text-amber-300 text-xs">✦</span>
                    </div>
                    <span class="text-sm font-medium">{{ $feature }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Panel - Login Form --}}
    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <div class="w-full max-w-md animate-fade-in-up">
            {{-- Mobile Logo --}}
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="ZEMS" class="mx-auto w-48 drop-shadow-xl">
            </div>

            {{-- Login Card --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200/50 p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-black text-slate-900">Welcome Back</h2>
                    <p class="mt-2 text-sm text-slate-500">Sign in to access your dashboard</p>
                </div>

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    {{-- Identifier --}}
                    <div>
                        <label for="identifier" class="block text-sm font-semibold text-slate-700 mb-2">Email or NIC Number</label>
                        <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm placeholder-slate-400"
                            placeholder="Enter your email or NIC">
                        @error('identifier')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm placeholder-slate-400 pr-12"
                                placeholder="Enter your password">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                        <label for="remember" class="text-sm text-slate-600">Remember me</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Sign In
                    </button>
                                </form>

                {{-- Direct Teacher Census Link --}}
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500 mb-2">Need to submit your teacher census details?</p>
                    <a href="{{ route('teacher.form') }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl border border-blue-200 bg-blue-50/60 hover:bg-blue-100/70 text-blue-700 text-xs font-bold transition">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Teacher Information Form (Census)
                    </a>
                </div>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">© {{ date('Y') }} Zonal Education Office, Wilgamuwa.</p>
                    <p class="text-xs text-slate-400 mt-1">
                        Designed and Developed by
                        <a href="https://www.facebook.com/bhathiyakonage" target="_blank" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">Bhathiya Konage</a>.
                    </p>
                </div>
            </div>

            <p class="mt-6 text-center text-sm text-slate-500">
                Need help? Contact your zone administrator
            </p>
        </div>
    </div>
</div>
@endsection
