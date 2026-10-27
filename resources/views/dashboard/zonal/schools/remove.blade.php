@extends('layouts.dashboard')
@section('title', 'Remove School')

@section('content')
<div class="space-y-6 animate-fade-in">

    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Remove School</h1>
                <p class="zems-header-subtitle">Deactivate or permanently delete schools from the system</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="zems-card-base p-6 md:p-8 max-w-3xl">
        <form method="POST" action="" id="removeSchoolForm">
            @csrf
            @method('DELETE')

            <div class="space-y-6">

                {{-- Select School --}}
                <div class="grid grid-cols-1 sm:grid-cols-[200px_1fr] items-center gap-2 sm:gap-4">
                    <label class="zems-input-label mb-0">Select School</label>
                    <select id="schoolSelect" name="school_id" required class="zems-input w-full">
                        <option value="">-- Search or Select School --</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->censusNo ?? 'NO_CENSUS' }} | {{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Reason for Removal --}}
                <div class="grid grid-cols-1 sm:grid-cols-[200px_1fr] items-center gap-2 sm:gap-4">
                    <label class="zems-input-label mb-0">Reason for Removal</label>
                    <input type="text" name="reason" required placeholder="e.g. School Closed, Merged, or Data Error" class="zems-input w-full">
                </div>

                {{-- Effective Date --}}
                <div class="grid grid-cols-1 sm:grid-cols-[200px_1fr] items-center gap-2 sm:gap-4">
                    <label class="zems-input-label mb-0">Effective Date</label>
                    <div class="w-full sm:w-64">
                        <input type="date" name="effective_date" required class="zems-input w-full">
                    </div>
                </div>
            </div>

            {{-- Warning Box --}}
            <div class="mt-8 pt-6 border-t border-slate-100">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex gap-3">
                    <div class="p-2 bg-red-100 rounded-lg h-fit">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-900 text-sm">Warning Action</h4>
                        <p class="text-sm text-red-700 mt-1">
                            This action will permanently delete the school record and unlink associated teachers. This cannot be undone.
                        </p>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="removeBtn" class="zems-btn-primary !bg-red-600 hover:!bg-red-700 w-fit px-8">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Remove School
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('removeSchoolForm');
    const schoolSelect = document.getElementById('schoolSelect');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const schoolId = schoolSelect.value;
        if (!schoolId) {
            alert('Please select a school.');
            return;
        }

        if (!confirm('Are you absolutely sure you want to remove this school? This action cannot be undone.')) {
            return;
        }

        // Set the form action dynamically to the destroy route
        form.action = '{{ url("dashboard/zonal/schools") }}/' + schoolId;
        form.submit();
    });
});
</script>
@endsection
