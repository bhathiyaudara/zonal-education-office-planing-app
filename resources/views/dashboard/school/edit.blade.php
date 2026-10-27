@extends('layouts.dashboard')

@section('title', 'Edit School Profile')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h1 class="zems-header-title">Edit School Information</h1>
                <p class="zems-header-subtitle">Update your school's details and contact information.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-900 border border-emerald-200 p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('school.profile.update') }}" class="zems-card-base p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Basic Information --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="zems-input-label">School Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $school->name) }}" required class="zems-input">
                            @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="zems-input-label">Census No</label>
                            <input type="text" name="censusNo" value="{{ old('censusNo', $school->censusNo) }}" class="zems-input">
                            @error('censusNo') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="zems-input-label">School Type</label>
                            <select name="schoolType" class="zems-input">
                                <option value="">Select Type</option>
                                <option value="1AB" {{ old('schoolType', $school->schoolType) == '1AB' ? 'selected' : '' }}>1AB</option>
                                <option value="1C" {{ old('schoolType', $school->schoolType) == '1C' ? 'selected' : '' }}>1C</option>
                                <option value="Type 2" {{ old('schoolType', $school->schoolType) == 'Type 2' ? 'selected' : '' }}>Type 2</option>
                                <option value="Type 3" {{ old('schoolType', $school->schoolType) == 'Type 3' ? 'selected' : '' }}>Type 3</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="zems-input-label">Education Division</label>
                            <select name="eduDivision" class="zems-input">
                                <option value="">Select Division</option>
                                <option value="Wilgamuwa" {{ old('eduDivision', $school->eduDivision) == 'Wilgamuwa' ? 'selected' : '' }}>Wilgamuwa</option>
                                <option value="Laggala" {{ old('eduDivision', $school->eduDivision) == 'Laggala' ? 'selected' : '' }}>Laggala</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="zems-input-label">Grade Start</label>
                                <input type="number" name="gradeStart" min="1" max="13" value="{{ old('gradeStart', $school->gradeStart) }}" class="zems-input">
                            </div>
                            <div>
                                <label class="zems-input-label">Grade End</label>
                                <input type="number" name="gradeEnd" min="1" max="13" value="{{ old('gradeEnd', $school->gradeEnd) }}" class="zems-input">
                            </div>
                        </div>

                        <div>
                            <label class="zems-input-label">Sanctioned Positions</label>
                            <input type="number" name="sanctionedPositions" min="0" value="{{ old('sanctionedPositions', $school->sanctionedPositions) }}" class="zems-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact & Admin Information --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Contact Details</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="zems-input-label">Address</label>
                            <input type="text" name="address" value="{{ old('address', $school->address) }}" class="zems-input">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="zems-input-label">Contact Number</label>
                                <input type="text" name="contactNumber" value="{{ old('contactNumber', $school->contactNumber) }}" class="zems-input">
                            </div>
                            <div>
                                <label class="zems-input-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $school->email) }}" class="zems-input">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mt-8 mb-4 border-b border-slate-100 pb-2">Administration</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="zems-input-label">Principal Name</label>
                            <input type="text" name="principalName" value="{{ old('principalName', $school->principalName) }}" class="zems-input">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="zems-input-label">Principal NIC</label>
                                <input type="text" name="principalNic" value="{{ old('principalNic', $school->principalNic) }}" class="zems-input">
                            </div>
                            <div>
                                <label class="zems-input-label">Principal Mobile</label>
                                <input type="text" name="principalMobile" value="{{ old('principalMobile', $school->principalMobile) }}" class="zems-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="zems-btn-secondary">Cancel</a>
            <button type="submit" class="zems-btn-primary px-8">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
