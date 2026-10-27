@extends('layouts.base')

@section('body')
<div x-data="toast" class="relative min-h-screen">
    {{-- Toast Notification --}}
    <div x-show="show" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-6"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-6"
         class="fixed top-6 right-6 z-[100] max-w-sm" style="display:none;">
        <div :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
             class="text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 backdrop-blur-sm">
            <template x-if="type === 'success'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </template>
            <span x-text="message" class="text-sm font-medium"></span>
        </div>
    </div>

    <div x-data="sidebar" class="flex min-h-screen">
        {{-- Mobile Header --}}
        <header class="md:hidden fixed top-0 left-0 right-0 h-16 sidebar-gradient text-white z-50 shadow-lg no-print">
            <div class="flex items-center justify-between h-full px-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="ZEMS" class="h-10 w-auto">
                </div>
                <button @click="toggle" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                    <svg x-show="!open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </header>

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Overlay --}}
        <div x-show="open" @click="close" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 md:hidden animate-fade-in" style="display:none;"></div>

        {{-- Main Content --}}
        <main class="flex-1 md:ml-72 pt-16 md:pt-0 flex flex-col min-h-screen">
            <div class="p-4 md:p-6 lg:p-8 flex-1 w-full">
                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="mt-auto border-t border-slate-200 bg-white/50 backdrop-blur-sm no-print">
                <div class="px-4 md:px-6 lg:px-8 py-6 text-center">
                    <p class="text-sm text-slate-600">© {{ date('Y') }} Zonal Education Office, Wilgamuwa. Designed and Developed by
                        <a href="https://www.facebook.com/bhathiyakonage" target="_blank" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">Bhathiya Konage</a>.
                    </p>
                </div>
            </footer>
        </main>
    </div>
</div>
@endsection
