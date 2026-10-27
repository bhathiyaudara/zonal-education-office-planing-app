@extends('layouts.dashboard')
@section('title', $teacher->fullName)

@section('content')
<div class="space-y-6 animate-fade-in">
    <a href="{{ route('zonal.teachers') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 font-semibold hover:text-blue-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Teachers
    </a>

    {{-- Profile Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="w-20 h-20 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white text-2xl font-black border border-white/20 shadow-lg shrink-0">
                {{ strtoupper(substr($teacher->nameWithInitials, 0, 2)) }}
            </div>
            <div>
                <h1 class="zems-header-title">{{ $teacher->fullName }}</h1>
                <p class="zems-header-subtitle">{{ $teacher->nameWithInitials }}</p>
                <div class="flex flex-wrap gap-3 mt-3">
                    <span class="zems-badge-secondary bg-white/20 text-white border-white/30 backdrop-blur-sm">{{ $teacher->nic }}</span>
                    @if($teacher->school)
                    <span class="zems-badge-success !bg-emerald-500/30 !text-emerald-100 !border-emerald-500/50 backdrop-blur-sm">{{ $teacher->school->name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Personal Info --}}
        <div class="zems-card-base p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Personal Information</h3>
            </div>
            <div class="space-y-4 text-sm">
                @foreach([
                    'Date of Birth' => $teacher->dateOfBirth?->format('Y-m-d'),
                    'Gender' => $teacher->gender,
                    'Address' => $teacher->address,
                    'Phone' => $teacher->phone,
                    'WhatsApp' => $teacher->whatsapp,
                    'Email' => $teacher->email,
                    'Marital Status' => $teacher->maritalStatus,
                ] as $label => $value)
                <div class="flex justify-between py-2 border-b border-slate-100 last:border-0 last:pb-0">
                    <span class="font-bold text-slate-500">{{ $label }}</span>
                    <span class="font-bold text-slate-800 text-right">{{ $value ?: '-' }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Professional Info --}}
        <div class="zems-card-base p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Professional Details</h3>
            </div>
            <div class="space-y-4 text-sm">
                @foreach([
                    'File Number' => $teacher->fileNumber,
                    'Salary Number' => $teacher->salaryNumber,
                    'Service Grade' => $teacher->serviceGrade,
                    'Current Position' => $teacher->currentPosition,
                    'Appointment Type' => $teacher->appointmentType,
                    'Appointment Subject' => $teacher->appointmentSubject,
                    'Appointment Date' => $teacher->appointmentDate?->format('Y-m-d'),
                    'Joined School' => $teacher->joinedCurrentSchoolDate?->format('Y-m-d'),
                    'Training Status' => $teacher->trainingStatus,
                    'Locked (Zonal)' => $teacher->isLockedByZonal ? 'Yes' : 'No',
                    'Locked (School)' => $teacher->isLockedBySchool ? 'Yes' : 'No',
                ] as $label => $value)
                <div class="flex justify-between py-2 border-b border-slate-100 last:border-0 last:pb-0">
                    <span class="font-bold text-slate-500">{{ $label }}</span>
                    <span class="font-bold text-slate-800 text-right">{{ $value ?: '-' }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Subjects --}}
    <div class="zems-card-base p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Assigned Subjects</h3>
        </div>
        <div class="flex flex-wrap gap-3">
            @forelse($teacher->subjects as $ts)
            <span class="zems-badge-primary bg-emerald-50 border-emerald-200 text-emerald-800">
                {{ $ts->subject->name ?? 'Unknown' }}
            </span>
            @empty
            <p class="text-slate-400 text-sm font-semibold italic border-2 border-dashed border-slate-200 w-full p-4 rounded-xl text-center">No subjects assigned.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
