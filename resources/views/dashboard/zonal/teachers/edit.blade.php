@extends('layouts.dashboard')
@section('title', 'Edit Teacher - ' . $teacher->nameWithInitials)

@section('content')
<div class="space-y-6 animate-fade-in">
    <a href="{{ route('zonal.teachers') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 font-semibold hover:text-blue-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Teachers
    </a>

    {{-- Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white text-2xl font-black border border-white/20 shadow-lg shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Edit Teacher Profile</h1>
                <p class="zems-header-subtitle">{{ $teacher->fullName }}</p>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:16px;padding:16px 20px;display:flex;align-items:flex-start;gap:12px;">
        <svg style="width:20px;height:20px;color:#ef4444;flex-shrink:0;margin-top:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p style="font-size:14px;font-weight:700;color:#991b1b;">Please fix the following errors:</p>
            <ul style="margin-top:4px;font-size:13px;color:#b91c1c;list-style:disc;padding-left:20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
    <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:16px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
        <svg style="width:20px;height:20px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p style="font-size:14px;font-weight:600;color:#065f46;">{{ session('success') }}</p>
    </div>
    @endif

    <form action="{{ route('zonal.teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Personal Information --}}
        <div class="zems-card-base p-6 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Personal Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Name with Initials --}}
                <div>
                    <label class="zems-input-label">Name with Initials *</label>
                    <input type="text" name="nameWithInitials" value="{{ old('nameWithInitials', $teacher->nameWithInitials) }}" required class="zems-input">
                </div>
                {{-- Full Name --}}
                <div>
                    <label class="zems-input-label">Full Name *</label>
                    <input type="text" name="fullName" value="{{ old('fullName', $teacher->fullName) }}" required class="zems-input">
                </div>
                {{-- NIC --}}
                <div>
                    <label class="zems-input-label">NIC Number *</label>
                    <input type="text" name="nic" value="{{ old('nic', $teacher->nic) }}" required class="zems-input">
                </div>
                {{-- Date of Birth --}}
                <div>
                    <label class="zems-input-label">Date of Birth</label>
                    <input type="date" name="dateOfBirth" value="{{ old('dateOfBirth', $teacher->dateOfBirth?->format('Y-m-d')) }}" class="zems-input">
                </div>
                {{-- Gender --}}
                <div>
                    <label class="zems-input-label">Gender</label>
                    <select name="gender" class="zems-input">
                        <option value="">-- Select --</option>
                        <option value="Male" {{ old('gender', $teacher->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $teacher->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                {{-- Marital Status --}}
                <div>
                    <label class="zems-input-label">Marital Status</label>
                    <select name="maritalStatus" class="zems-input">
                        <option value="">-- Select --</option>
                        <option value="Single" {{ old('maritalStatus', $teacher->maritalStatus) == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ old('maritalStatus', $teacher->maritalStatus) == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Divorced" {{ old('maritalStatus', $teacher->maritalStatus) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="Widowed" {{ old('maritalStatus', $teacher->maritalStatus) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                </div>
                {{-- Phone --}}
                <div>
                    <label class="zems-input-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="zems-input">
                </div>
                {{-- WhatsApp --}}
                <div>
                    <label class="zems-input-label">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $teacher->whatsapp) }}" class="zems-input">
                </div>
                {{-- Email --}}
                <div>
                    <label class="zems-input-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="zems-input">
                </div>
                {{-- Address --}}
                <div class="col-span-1 md:col-span-3">
                    <label class="zems-input-label">Address</label>
                    <input type="text" name="address" value="{{ old('address', $teacher->address) }}" class="zems-input">
                </div>
            </div>
        </div>

        {{-- Professional Details --}}
        <div class="zems-card-base p-6 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Professional Details</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- School --}}
                <div>
                    <label class="zems-input-label">Assigned School</label>
                    <select name="schoolId" class="zems-input">
                        <option value="">-- Select School --</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('schoolId', $teacher->schoolId) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- File Number --}}
                <div>
                    <label class="zems-input-label">File Number</label>
                    <input type="text" name="fileNumber" value="{{ old('fileNumber', $teacher->fileNumber) }}" class="zems-input">
                </div>
                {{-- Salary Number --}}
                <div>
                    <label class="zems-input-label">Salary Number</label>
                    <input type="text" name="salaryNumber" value="{{ old('salaryNumber', $teacher->salaryNumber) }}" class="zems-input">
                </div>
                {{-- Current Position --}}
                <div>
                    <label class="zems-input-label">Current Position</label>
                    <select name="currentPosition" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Principal', 'Deputy Principal', 'Assistant Principal', 'Teacher'] as $pos)
                        <option value="{{ $pos }}" {{ old('currentPosition', $teacher->currentPosition) == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Service Grade --}}
                <div>
                    <label class="zems-input-label">Service Grade</label>
                    <select name="serviceGrade" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['SLPS Grade III', 'SLPS Grade II', 'SLPS Grade I', 'SLTS Grade III', 'SLTS Grade II', 'SLTS Grade I'] as $grade)
                        <option value="{{ $grade }}" {{ old('serviceGrade', $teacher->serviceGrade) == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Service Grade Appointed Date --}}
                <div>
                    <label class="zems-input-label">Grade Appointed Date</label>
                    <input type="date" name="serviceGradeAppointedDate" value="{{ old('serviceGradeAppointedDate', $teacher->serviceGradeAppointedDate?->format('Y-m-d')) }}" class="zems-input">
                </div>
                {{-- Appointment Type --}}
                <div>
                    <label class="zems-input-label">Appointment Type</label>
                    <select name="appointmentType" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Permanent', 'Temporary', 'Acting', 'Contract'] as $type)
                        <option value="{{ $type }}" {{ old('appointmentType', $teacher->appointmentType) == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Appointment Date --}}
                <div>
                    <label class="zems-input-label">Appointment Date</label>
                    <input type="date" name="appointmentDate" value="{{ old('appointmentDate', $teacher->appointmentDate?->format('Y-m-d')) }}" class="zems-input">
                </div>
                {{-- Appointment Subject --}}
                <div>
                    <label class="zems-input-label">Appointment Subject</label>
                    <input type="text" name="appointmentSubject" value="{{ old('appointmentSubject', $teacher->appointmentSubject) }}" class="zems-input">
                </div>
                {{-- Appointment Medium --}}
                <div>
                    <label class="zems-input-label">Appointment Medium</label>
                    <select name="appointmentMedium" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Sinhala', 'Tamil', 'English'] as $medium)
                        <option value="{{ $medium }}" {{ old('appointmentMedium', $teacher->appointmentMedium) == $medium ? 'selected' : '' }}>{{ $medium }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Training Status --}}
                <div>
                    <label class="zems-input-label">Training Status</label>
                    <select name="trainingStatus" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['Trained', 'Untrained', 'In Training'] as $ts)
                        <option value="{{ $ts }}" {{ old('trainingStatus', $teacher->trainingStatus) == $ts ? 'selected' : '' }}>{{ $ts }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Nature of Appointment --}}
                <div>
                    <label class="zems-input-label">Nature of Appointment</label>
                    <select name="natureOfAppointment" class="zems-input">
                        <option value="">-- Select --</option>
                        @foreach(['First Appointment', 'Transfer', 'Promotion', 'Attachment'] as $nature)
                        <option value="{{ $nature }}" {{ old('natureOfAppointment', $teacher->natureOfAppointment) == $nature ? 'selected' : '' }}>{{ $nature }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Joined Current School --}}
                <div>
                    <label class="zems-input-label">Joined Current School</label>
                    <input type="date" name="joinedCurrentSchoolDate" value="{{ old('joinedCurrentSchoolDate', $teacher->joinedCurrentSchoolDate?->format('Y-m-d')) }}" class="zems-input">
                </div>
            </div>
        </div>

        {{-- Teaching Subjects --}}
        <div class="zems-card-base p-6 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Teaching Subjects</h3>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach($subjects as $subject)
                <label class="zems-badge-secondary cursor-pointer border-slate-200 hover:border-violet-300 hover:bg-violet-50 transition-colors flex items-center gap-2 px-3 py-1.5 has-[:checked]:border-violet-500 has-[:checked]:bg-violet-50 has-[:checked]:text-violet-700">
                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                           {{ in_array($subject->id, old('subjects', $teacherSubjectIds)) ? 'checked' : '' }}
                           class="w-4 h-4 text-violet-600 rounded focus:ring-violet-500">
                    {{ $subject->name }}
                </label>
                @endforeach
                @if($subjects->isEmpty())
                <p class="text-sm font-semibold italic border-2 border-dashed border-slate-200 w-full p-4 rounded-xl text-center text-slate-400">No subjects available. Add subjects in Subject Management first.</p>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('zonal.teachers') }}" class="zems-btn-secondary px-6">
                Cancel
            </a>
            <button type="submit" class="zems-btn-primary px-8">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
