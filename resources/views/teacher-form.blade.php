@extends('layouts.base')
@section('title', 'Teacher Information Form — Wilgamuwa Zone')

@section('body')
<div class="min-h-screen bg-slate-50 pb-20">
    {{-- Header --}}
    <header class="border-b-4 border-amber-500 bg-gradient-to-r from-blue-900 to-indigo-950 px-6 py-10 text-white shadow-lg">
        <div class="mx-auto max-w-4xl">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="ZEMS Logo" class="h-12 w-auto bg-white/10 rounded-xl p-1 backdrop-blur-sm border border-white/20">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-400">Wilgamuwa Education Zone · 52204</p>
                    <h1 class="mt-1 text-3xl sm:text-4xl font-black tracking-tight">Teacher Information Form</h1>
                </div>
            </div>
            <p class="mt-3 max-w-2xl text-sm text-slate-300">Submit Sri Lankan school teacher census details for the Wilgamuwa education zone. Fields marked with <span class="text-rose-400 font-bold">*</span> are required.</p>
        </div>
    </header>

    {{-- Main Form Container --}}
    <main class="mx-auto mt-8 max-w-4xl px-4">
        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 shadow-sm">
            <div class="flex items-center gap-2 font-bold text-rose-800 mb-1">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Please correct the errors below before submitting:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-5 text-xs text-rose-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="public-teacher-form" action="{{ route('teacher.form.submit') }}" method="POST" class="space-y-6">
            @csrf

            @include('dashboard.zonal.teachers.form_fields')

            {{-- Form Footer Actions --}}
            <div class="flex items-center justify-between flex-wrap gap-4 pt-4 border-t border-slate-200">
                <button type="button" id="preview-pdf-btn" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-slate-300 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview PDF
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-500/25 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Submit Details
                </button>
            </div>
        </form>
    </main>

    <footer class="mt-20 border-t border-slate-200 py-8 text-center text-xs text-slate-500">
        <p>© {{ date('Y') }} Zonal Education Office, Wilgamuwa. All Rights Reserved.</p>
    </footer>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('js/teacherPdfHelper.js') }}"></script>
<script>
    function getFormValues() {
        const form = document.getElementById('public-teacher-form');
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
        let schoolName = '';
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
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            const { values, schoolName, otherSchoolName } = getFormValues();
            previewTeacherPdf(values, schoolName, otherSchoolName);
        });
    }
</script>
@endpush
