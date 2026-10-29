@extends('layouts.base')
@section('title', 'Submission Successful — Wilgamuwa Zone')

@section('body')
<main class="flex min-h-screen items-center justify-center bg-slate-50 p-6">
    <div class="max-w-md w-full rounded-2xl border border-slate-200 bg-white p-8 md:p-10 text-center shadow-xl">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-md shadow-emerald-500/10">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Thank You!</h1>
        <p class="mt-2 text-sm text-slate-500">Your teacher census information has been successfully recorded in the Zonal Education Management System.</p>

        <div class="my-6 rounded-xl bg-slate-50 p-4 border border-slate-200 text-left text-xs space-y-1">
            <p><span class="font-bold text-slate-600">Teacher:</span> {{ $teacher->fullName ?: $teacher->nameWithInitials }}</p>
            <p><span class="font-bold text-slate-600">NIC:</span> <span class="font-mono font-bold">{{ $teacher->nic }}</span></p>
            <p><span class="font-bold text-slate-600">School:</span> {{ $schoolName }}</p>
        </div>

        {{-- PDF Actions --}}
        <div class="space-y-3">
            <div class="flex flex-wrap justify-center gap-3">
                <button type="button" id="preview-pdf-btn" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview PDF
                </button>
                <button type="button" id="download-pdf-btn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download PDF Copy
                </button>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('teacher.form') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Submit Another Teacher
                </a>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('js/teacherPdfHelper.js') }}"></script>
<script>
    const values = @json($values ?? []);
    const schoolName = @json($schoolName ?? '');
    const otherSchoolName = @json($otherSchoolName ?? '');

    document.getElementById('preview-pdf-btn').addEventListener('click', function() {
        previewTeacherPdf(values, schoolName, otherSchoolName);
    });

    document.getElementById('download-pdf-btn').addEventListener('click', function() {
        downloadTeacherPdf(values, schoolName, otherSchoolName);
    });
</script>
@endpush
