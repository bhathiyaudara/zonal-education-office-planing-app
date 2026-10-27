@extends('layouts.dashboard')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="zems-header">
        <div class="flex items-center justify-between flex-wrap gap-4 w-full">
            <div>
                <h1 class="zems-header-title">My Profile</h1>
                <p class="zems-header-subtitle">View your teacher profile and manage requests</p>
            </div>
        </div>
    </div>

    @if($teacher)
    {{-- Profile Card --}}
    <div class="zems-card-base p-6">
        <div class="flex items-start gap-6 flex-wrap">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                {{ strtoupper(substr($teacher->nameWithInitials ?? 'T', 0, 2)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-slate-900">{{ $teacher->fullName }}</h2>
                <p class="text-slate-500">{{ $teacher->nameWithInitials }}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ $teacher->nic }}</span>
                    @if($teacher->school)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">{{ $teacher->school->name }}</span>
                    @endif
                    @if($teacher->appointmentSubject)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-violet-100 text-violet-700">{{ $teacher->appointmentSubject }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Personal Info --}}
        <div class="zems-card-base p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                Personal Information
            </h3>
            <div class="space-y-3 text-sm">
                @foreach([
                    'Date of Birth' => $teacher->dateOfBirth ? $teacher->dateOfBirth->format('Y-m-d') : '-',
                    'Gender' => $teacher->gender ?: '-',
                    'Address' => $teacher->address ?: '-',
                    'Phone' => $teacher->phone ?: '-',
                    'WhatsApp' => $teacher->whatsapp ?: '-',
                    'Email' => $teacher->email ?: '-',
                    'Marital Status' => $teacher->maritalStatus ?: '-',
                ] as $label => $value)
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <span class="font-semibold text-slate-500">{{ $label }}</span>
                    <span class="text-slate-800 text-right">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Professional Info --}}
        <div class="zems-card-base p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                Professional Details
            </h3>
            <div class="space-y-3 text-sm">
                @foreach([
                    'File Number' => $teacher->fileNumber ?: '-',
                    'Salary Number' => $teacher->salaryNumber ?: '-',
                    'Service Grade' => $teacher->serviceGrade ?: '-',
                    'Current Position' => $teacher->currentPosition ?: '-',
                    'Appointment Type' => $teacher->appointmentType ?: '-',
                    'Appointment Date' => $teacher->appointmentDate ? $teacher->appointmentDate->format('Y-m-d') : '-',
                    'Joined School' => $teacher->joinedCurrentSchoolDate ? $teacher->joinedCurrentSchoolDate->format('Y-m-d') : '-',
                    'Training Status' => $teacher->trainingStatus ?: '-',
                ] as $label => $value)
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <span class="font-semibold text-slate-500">{{ $label }}</span>
                    <span class="text-slate-800 text-right">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('teacher.salary') }}" class="zems-card-base p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Salary Requests</h3>
                    <p class="text-sm text-slate-500">Request salary certificates</p>
                </div>
                <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 ml-auto transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
        <a href="{{ route('teacher.transfer') }}" class="zems-card-base p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Transfer Request</h3>
                    <p class="text-sm text-slate-500">Request a school transfer</p>
                </div>
                <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 ml-auto transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    @else
    <div class="zems-card-base p-12 text-center border-dashed border-2">
        <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">No Teacher Profile Found</h3>
        <p class="text-sm text-slate-500 mt-2">Your account is not linked to a teacher profile. Contact your school administrator.</p>
    </div>
    @endif
</div>
@endsection
