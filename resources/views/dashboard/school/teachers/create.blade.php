@extends('layouts.dashboard')
@section('title', 'Add New Teacher - ' . $school->name)

@section('content')
<div class="space-y-6 animate-fade-in max-w-5xl mx-auto">
    <a href="{{ route('school.teachers') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 font-semibold hover:text-emerald-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Teachers
    </a>

    {{-- Header --}}
    <div class="zems-header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white text-2xl font-black border border-white/20 shadow-lg shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                </div>
                <div>
                    <h1 class="zems-header-title">Teacher Information Form</h1>
                    <p class="zems-header-subtitle">{{ $school->name }} (Census No: {{ $school->censusNo }})</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="top-preview-pdf-btn" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition backdrop-blur-sm border border-white/30">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview PDF
                </button>
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

    <form id="teacher-create-form" action="{{ route('school.teachers.store') }}" method="POST" class="space-y-6">
        @csrf

        @include('dashboard.zonal.teachers.form_fields', ['teacher' => (object)['census_no' => $school->censusNo]])

        {{-- Form Actions --}}
        <div class="flex items-center justify-between flex-wrap gap-4 pt-4 border-t border-slate-200">
            <a href="{{ route('school.teachers') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Cancel</a>
            
            <div class="flex items-center gap-3">
                <button type="button" id="preview-pdf-btn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview PDF
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-lg shadow-emerald-500/25 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Teacher Details
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('js/teacherPdfHelper.js') }}"></script>
<script>
    function getFormValues() {
        const form = document.getElementById('teacher-create-form');
        const formData = new FormData(form);
        const obj = {};
        for (let [k, v] of formData.entries()) {
            if (k.endsWith('[]')) {
                const cleanKey = k.slice(0, -2);
                if (!obj[cleanKey]) obj[cleanKey] = [];
                obj[cleanKey].push(v);
            } else {
                obj[k] = v;
            }
        }
        if (Array.isArray(obj['profall'])) obj['profall'] = obj['profall'].join('|');
        if (Array.isArray(obj['otherschool_days'])) obj['otherschool_days'] = obj['otherschool_days'].join('|');

        const census = obj['census_no'];
        let schoolName = @json($school->name);
        if (window.TEACHER_OPTIONS && window.TEACHER_OPTIONS.schools && census) {
            const sc = window.TEACHER_OPTIONS.schools.find(s => s.censusNo === census);
            if (sc) schoolName = sc.name;
        }

        const otherCensus = obj['otherschool_census'];
        let otherSchoolName = '';
        if (window.TEACHER_OPTIONS && window.TEACHER_OPTIONS.schools && otherCensus) {
            const osc = window.TEACHER_OPTIONS.schools.find(s => s.censusNo === otherCensus);
            if (osc) otherSchoolName = osc.name;
        }

        return { values: obj, schoolName, otherSchoolName };
    }

    const previewBtn = document.getElementById('preview-pdf-btn');
    const topPreviewBtn = document.getElementById('top-preview-pdf-btn');

    function triggerPreview() {
        const { values, schoolName, otherSchoolName } = getFormValues();
        previewTeacherPdf(values, schoolName, otherSchoolName);
    }

    if (previewBtn) previewBtn.addEventListener('click', triggerPreview);
    if (topPreviewBtn) topPreviewBtn.addEventListener('click', triggerPreview);
</script>
@endpush
