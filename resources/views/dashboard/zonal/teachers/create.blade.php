@extends('layouts.dashboard')
@section('title', 'Add New Teacher')

@section('content')
<div class="space-y-6 animate-fade-in">
    <a href="{{ route('zonal.teachers') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 font-semibold hover:text-blue-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Teachers
    </a>

    {{-- Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white font-black border border-white/20 shadow-lg">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Add New Teacher</h1>
                <p class="zems-header-subtitle">Create a new teacher profile and user account</p>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-bold text-red-800">Please fix the following errors:</p>
            <ul class="mt-1 text-sm text-red-600 list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('zonal.teachers.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Personal Information --}}
        <div class="zems-card-base p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Personal Information</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                {{-- Name with Initials --}}
                <div>
                    <label class="zems-input-label">Name with Initials *</label>
                    <input type="text" name="nameWithInitials" value="{{ old('nameWithInitials') }}" required placeholder="e.g. A.B. Perera" class="zems-input">
                </div>
                {{-- Full Name --}}
                <div>
                    <label class="zems-input-label">Full Name *</label>
                    <input type="text" name="fullName" value="{{ old('fullName') }}" required placeholder="e.g. Aruma Bedde Perera" class="zems-input">
                </div>
                {{-- NIC --}}
                <div>
                    <label class="zems-input-label">NIC Number *</label>
                    <input type="text" name="nic" value="{{ old('nic') }}" required placeholder="e.g. 199012345678" class="zems-input">
                </div>
                {{-- Date of Birth --}}
                <div>
                    <label class="zems-input-label">Date of Birth *</label>
                    <input type="date" name="dateOfBirth" value="{{ old('dateOfBirth') }}" required class="zems-input">
                </div>
                {{-- Gender --}}
                <div>
                    <label class="zems-input-label">Gender *</label>
                    <select name="gender" required class="zems-input">
                        <option value="">-- Select --</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                {{-- Marital Status --}}
                <div>
                    <label class="zems-input-label">Marital Status</label>
                    <select name="maritalStatus" class="zems-input">
                        <option value="">-- Select --</option>
                        <option value="Single" {{ old('maritalStatus') == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ old('maritalStatus') == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Divorced" {{ old('maritalStatus') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="Widowed" {{ old('maritalStatus') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                </div>
                {{-- Phone --}}
                <div>
                    <label class="zems-input-label">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="10 digits" class="zems-input">
                </div>
                {{-- WhatsApp --}}
                <div>
                    <label class="zems-input-label">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Optional" class="zems-input">
                </div>
                {{-- Email --}}
                <div>
                    <label class="zems-input-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Optional (defaults to NIC@wilgamuwa.com)" class="zems-input">
                </div>
                {{-- Address --}}
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="zems-input-label">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Permanent residential address" class="zems-input">
                </div>
            </div>
        </div>

        {{-- Professional Details --}}
        <div class="zems-card-base p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Professional Details</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                {{-- School --}}
                <div>
                    <label class="zems-input-label">Assigned School</label>
                    <select name="schoolId" class="zems-input">
                        <option value="">-- Select School --</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('schoolId') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- File Number --}}
                <div>
                    <label class="zems-input-label">File Number</label>
                    <input type="text" name="fileNumber" value="{{ old('fileNumber') }}" class="zems-input">
                </div>
                {{-- Salary Number --}}
                <div>
                    <label class="zems-input-label">Salary Number</label>
                    <input type="text" name="salaryNumber" value="{{ old('salaryNumber') }}" class="zems-input">
                </div>
                {{-- Current Position --}}
                <div>
                    <label class="zems-input-label">Current Position</label>
                    <select name="currentPosition" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Principal', 'Deputy Principal', 'Assistant Principal', 'Teacher'] as $pos)
                        <option value="{{ $pos }}" {{ old('currentPosition', 'Teacher') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Service Grade --}}
                <div>
                    <label class="zems-input-label">Service Grade</label>
                    <select name="serviceGrade" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['SLPS Grade III', 'SLPS Grade II', 'SLPS Grade I', 'SLTS Grade III', 'SLTS Grade II', 'SLTS Grade I'] as $grade)
                        <option value="{{ $grade }}" {{ old('serviceGrade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Service Grade Appointed Date --}}
                <div>
                    <label class="zems-input-label">Grade Appointed Date</label>
                    <input type="date" name="serviceGradeAppointedDate" value="{{ old('serviceGradeAppointedDate') }}" class="zems-input">
                </div>
                {{-- Appointment Type --}}
                <div>
                    <label class="zems-input-label">Appointment Type</label>
                    <select name="appointmentType" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Permanent', 'Temporary', 'Acting', 'Contract'] as $type)
                        <option value="{{ $type }}" {{ old('appointmentType') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Appointment Date --}}
                <div>
                    <label class="zems-input-label">First Appointment Date</label>
                    <input type="date" name="appointmentDate" value="{{ old('appointmentDate') }}" class="zems-input">
                </div>
                {{-- Appointment Subject --}}
                <div>
                    <label class="zems-input-label">Appointment Subject</label>
                    <input type="text" name="appointmentSubject" value="{{ old('appointmentSubject') }}" class="zems-input">
                </div>
                {{-- Appointment Medium --}}
                <div>
                    <label class="zems-input-label">Appointment Medium</label>
                    <select name="appointmentMedium" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Sinhala', 'Tamil', 'English'] as $medium)
                        <option value="{{ $medium }}" {{ old('appointmentMedium') == $medium ? 'selected' : '' }}>{{ $medium }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Training Status --}}
                <div>
                    <label class="zems-input-label">Training Status</label>
                    <select name="trainingStatus" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Trained', 'Untrained', 'In Training'] as $ts)
                        <option value="{{ $ts }}" {{ old('trainingStatus') == $ts ? 'selected' : '' }}>{{ $ts }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Nature of Appointment --}}
                <div>
                    <label class="zems-input-label">Nature of Appointment</label>
                    <select name="natureOfAppointment" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['First Appointment', 'Transfer', 'Promotion', 'Attachment'] as $nature)
                        <option value="{{ $nature }}" {{ old('natureOfAppointment') == $nature ? 'selected' : '' }}>{{ $nature }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Joined Current School --}}
                <div>
                    <label class="zems-input-label">Joined Current School</label>
                    <input type="date" name="joinedCurrentSchoolDate" value="{{ old('joinedCurrentSchoolDate') }}" class="zems-input">
                </div>
            </div>
        </div>

        {{-- Teaching Subjects --}}
        <div class="zems-card-base p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Teaching Subjects</h3>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach($subjects as $subject)
                <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 cursor-pointer text-sm font-semibold text-slate-700 transition-all hover:border-emerald-500 hover:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-800"
                       onmouseenter="this.style.borderColor='#10b981';this.style.background='#ecfdf5'" onmouseleave="if(!this.querySelector('input').checked){this.style.borderColor='#e2e8f0';this.style.background=''}">
                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                           {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}
                           class="w-4 h-4 accent-emerald-500 cursor-pointer"
                           onchange="let l=this.parentElement; if(this.checked){l.style.borderColor='#10b981';l.style.background='#ecfdf5';l.style.color='#047857'}else{l.style.borderColor='#e2e8f0';l.style.background='';l.style.color='#334155'}">
                    {{ $subject->name }}
                </label>
                @endforeach
                @if($subjects->isEmpty())
                <p class="text-sm text-slate-400 italic">No subjects available. Add subjects in Subject Management first.</p>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('zonal.teachers') }}" class="zems-btn-secondary">
                Cancel
            </a>
            <button type="submit" class="zems-btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Teacher
            </button>
        </div>
    </form>
</div>

<script>
// Highlight pre-checked subjects on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="subjects[]"]:checked').forEach(function(cb) {
        var label = cb.parentElement;
        label.style.borderColor = '#10b981';
        label.style.background = '#ecfdf5';
        label.style.color = '#047857';
    });
});
</script>
@endsection
