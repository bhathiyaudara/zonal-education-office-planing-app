@extends('layouts.dashboard')

@section('title', 'Zonal Admin Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    {{-- Welcome Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Welcome to Wilgamuwa - ZEOMS</h1>
                <p class="zems-header-subtitle">Zonal Education Office Management System — Manage schools, teachers, and administrative tasks efficiently.</p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
        $stats = [
            ['title' => 'Total Teachers', 'value' => $teacherCount, 'icon' => 'users', 'gradient' => 'from-blue-500 to-blue-600', 'link' => route('zonal.teachers')],
            ['title' => 'Total Schools', 'value' => $schoolCount, 'icon' => 'school', 'gradient' => 'from-emerald-500 to-emerald-600', 'link' => route('zonal.schools')],
            ['title' => 'Pending Transfers', 'value' => $pendingTransfers, 'icon' => 'transfer', 'gradient' => 'from-amber-500 to-orange-500', 'link' => route('zonal.transfers')],
            ['title' => 'Total Students', 'value' => number_format($totalStudents), 'icon' => 'students', 'gradient' => 'from-violet-500 to-purple-600', 'link' => '#'],
        ];
        @endphp

        @foreach($stats as $i => $stat)
        <a href="{{ $stat['link'] }}" class="group relative zems-card-base p-6 overflow-hidden hover:-translate-y-1 transition-all duration-300 animate-fade-in-up stagger-{{ $i + 1 }}">
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $stat['gradient'] }} flex items-center justify-center text-white shadow-lg mb-4">
                    @if($stat['icon'] === 'users')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    @elseif($stat['icon'] === 'school')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    @elseif($stat['icon'] === 'transfer')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    @endif
                </div>
                <p class="text-sm font-semibold text-slate-500">{{ $stat['title'] }}</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $stat['value'] }}</p>
            </div>
            <div class="absolute top-0 right-0 -translate-y-4 translate-x-4 w-24 h-24 bg-gradient-to-br {{ $stat['gradient'] }} rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
        </a>
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <div class="zems-card-base p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php
            $actions = [
                ['label' => 'Add School', 'route' => 'zonal.schools', 'color' => 'blue'],
                ['label' => 'Manage Teachers', 'route' => 'zonal.teachers', 'color' => 'emerald'],
                ['label' => 'View Transfers', 'route' => 'zonal.transfers', 'color' => 'amber'],
                ['label' => 'Audit Logs', 'route' => 'zonal.audit', 'color' => 'violet'],
            ];
            @endphp
            @foreach($actions as $action)
            <a href="{{ route($action['route']) }}"
               class="py-3 px-4 rounded-xl text-center font-semibold text-sm bg-{{ $action['color'] }}-50 text-{{ $action['color'] }}-700 hover:bg-{{ $action['color'] }}-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 border border-{{ $action['color'] }}-100">
                {{ $action['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
