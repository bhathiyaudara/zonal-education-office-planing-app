@extends('layouts.dashboard')
@section('title', 'Add Teacher')

@section('content')
<div class="space-y-6 animate-fade-in">
    <a href="{{ route('school.teachers') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 font-semibold hover:text-emerald-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Teachers
    </a>

    <div class="zems-header">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white text-2xl font-black border border-white/20 shadow-lg shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Add New Teacher</h1>
                <p class="zems-header-subtitle">Register a new teacher in {{ $school->name }}</p>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-600">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('school.teachers.store') }}" class="zems-card-base p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="zems-input-label">NIC Number *</label>
                <input type="text" name="nic" value="{{ old('nic') }}" required class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Name with Initials *</label>
                <input type="text" name="nameWithInitials" value="{{ old('nameWithInitials') }}" required class="zems-input">
            </div>
            <div class="md:col-span-2">
                <label class="zems-input-label">Full Name *</label>
                <input type="text" name="fullName" value="{{ old('fullName') }}" required class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Gender</label>
                <select name="gender" class="zems-input">
                    <option value="">Select...</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
            <div>
                <label class="zems-input-label">Date of Birth</label>
                <input type="date" name="dateOfBirth" value="{{ old('dateOfBirth') }}" class="zems-input">
            </div>
            <div class="md:col-span-2">
                <label class="zems-input-label">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Appointment Type</label>
                <input type="text" name="appointmentType" value="{{ old('appointmentType') }}" class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Appointment Date</label>
                <input type="date" name="appointmentDate" value="{{ old('appointmentDate') }}" class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Service Grade</label>
                <input type="text" name="serviceGrade" value="{{ old('serviceGrade') }}" class="zems-input">
            </div>
            <div>
                <label class="zems-input-label">Current Position</label>
                <input type="text" name="currentPosition" value="{{ old('currentPosition') }}" class="zems-input">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="zems-btn-primary !bg-emerald-600 hover:!bg-emerald-700">
                Register Teacher
            </button>
        </div>
    </form>
</div>
@endsection
