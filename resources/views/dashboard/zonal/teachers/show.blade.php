@extends('layouts.dashboard')
@section('title', $teacher->fullName ?: $teacher->nameWithInitials)

@php
    $formData = $teacher->form_data ?? [];
    $getVal = function($key, $default = '') use ($teacher, $formData) {
        if (isset($teacher->{$key}) && $teacher->{$key} !== null) {
            if ($teacher->{$key} instanceof \Carbon\Carbon || $teacher->{$key} instanceof \DateTimeInterface) {
                return $teacher->{$key}->format('Y-m-d');
            }
            return $teacher->{$key};
        }
        if (isset($formData[$key])) {
            return $formData[$key];
        }
        return $default;
    };
    $schoolName = $teacher->school ? $teacher->school->name : ($teacher->census_no ?: '-');
    $otherSchoolName = $teacher->attached_institution_name ?: ($teacher->otherSchool ? $teacher->otherSchool->name : ($getVal('otherschool_name') ?: '-'));
@endphp

@section('content')
<div class="space-y-6 animate-fade-in max-w-5xl mx-auto">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('zonal.teachers') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 font-semibold hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Teachers
        </a>
        <div class="flex items-center gap-2">
            <button type="button" id="show-preview-pdf-btn" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Preview PDF
            </button>
            <button type="button" id="show-download-pdf-btn" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download PDF
            </button>
            <a href="{{ route('zonal.teachers.edit', $teacher->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition shadow-md shadow-amber-500/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Profile
            </a>
        </div>
    </div>

    {{-- Profile Header --}}
    <div class="zems-header">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="w-20 h-20 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white text-2xl font-black border border-white/20 shadow-lg shrink-0">
                {{ strtoupper(substr($teacher->fullName ?: 'T', 0, 2)) }}
            </div>
            <div>
                <h1 class="zems-header-title">{{ $teacher->fullName }}</h1>
                <p class="zems-header-subtitle">{{ $teacher->fullName }}</p>
                <div class="flex flex-wrap gap-2.5 mt-3">
                    <span class="zems-badge-secondary bg-white/20 text-white border-white/30 backdrop-blur-sm font-mono">{{ $teacher->nic }}</span>
                    @if($teacher->school)
                    <span class="zems-badge-success !bg-emerald-500/30 !text-emerald-100 !border-emerald-500/50 backdrop-blur-sm">{{ $teacher->school->name }} ({{ $teacher->school->censusNo }})</span>
                    @endif
                    @if($teacher->position)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/20">{{ $teacher->position }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Census Sections Details --}}
    <div class="space-y-6">
        {{-- Section 01: School & Identity --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">01</span>
                <h3 class="text-base font-bold text-slate-800">School & Identity</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 text-sm">
                <div><span class="text-slate-400 block text-xs font-semibold">01–02. School Census & Name</span><span class="font-bold text-slate-800">{{ $schoolName }} ({{ $teacher->census_no ?: '-' }})</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">03. National Identity Card (NIC)</span><span class="font-bold text-slate-800 font-mono">{{ $teacher->nic }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">04. Pay Sheet / Emp No</span><span class="font-bold text-slate-800">{{ $teacher->paysheet ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">05. Title</span><span class="font-bold text-slate-800">{{ $teacher->title ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">Personal File Number</span><span class="font-bold text-slate-800">{{ $teacher->personal_file_no ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">06. Name as Paysheet</span><span class="font-bold text-slate-800">{{ $teacher->fullName ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">Date of Birth</span><span class="font-bold text-slate-800">{{ $teacher->dateOfBirth?->format('Y-m-d') ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">32. Mobile Phone No</span><span class="font-bold text-slate-800">{{ $teacher->phone ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">WhatsApp Number</span><span class="font-bold text-slate-800">{{ $teacher->whatsapp ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">Email Address</span><span class="font-bold text-slate-800">{{ $teacher->email ?: '-' }}</span></div>
                <div class="sm:col-span-2 md:col-span-3"><span class="text-slate-400 block text-xs font-semibold">Address</span><span class="font-medium text-slate-800">{{ $teacher->address ?: '-' }}</span></div>
            </div>
        </div>

        {{-- Section 02: Position & Salary --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">02</span>
                <h3 class="text-base font-bold text-slate-800">Position & Salary</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                <div><span class="text-slate-400 block text-xs font-semibold">07. Position held in this school</span><span class="font-bold text-slate-800">{{ $teacher->position ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">08. Salary paid by</span><span class="font-bold text-slate-800">{{ $teacher->salaryby ?: '-' }}</span></div>
                <div class="sm:col-span-2"><span class="text-slate-400 block text-xs font-semibold">09. Service base of this school</span><span class="font-medium text-slate-800">{{ $teacher->servicebased ?: '-' }}</span></div>
                <div class="sm:col-span-2"><span class="text-slate-400 block text-xs font-semibold">10. Source of salary paid</span><span class="font-medium text-slate-800">{{ $teacher->salarysource ?: '-' }}</span></div>
            </div>
        </div>

        {{-- Section 03: Personal Details --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">03</span>
                <h3 class="text-base font-bold text-slate-800">Personal Details</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-y-3 gap-x-6 text-sm">
                <div><span class="text-slate-400 block text-xs font-semibold">11. Gender</span><span class="font-bold text-slate-800">{{ $teacher->gender ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">12. Ethnicity</span><span class="font-bold text-slate-800">{{ implode(' - ', array_filter([$teacher->ethnicity, $teacher->ethnicity_other])) ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">13. Religion</span><span class="font-bold text-slate-800">{{ implode(' - ', array_filter([$teacher->religion, $teacher->religion_other])) ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">14. Marital Status</span><span class="font-bold text-slate-800">{{ implode(' - ', array_filter([$teacher->maritalStatus, $teacher->marital_other])) ?: '-' }}</span></div>
            </div>
        </div>

        {{-- Section 04: Qualifications --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">04</span>
                <h3 class="text-base font-bold text-slate-800">Qualifications</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                <div><span class="text-slate-400 block text-xs font-semibold">15. Highest Education Qualification</span><span class="font-bold text-slate-800">{{ $teacher->eduqua ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">16. Subject Stream studied in A/L</span><span class="font-bold text-slate-800">{{ $teacher->subject_stream ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">17. Field of Study in Basic Degree</span><span class="font-bold text-slate-800">{{ implode(' - ', array_filter([$teacher->field_study, $teacher->field_study_other])) ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">18. Highest Professional Qualification</span><span class="font-bold text-slate-800">{{ implode(' - ', array_filter([$teacher->profqua, $teacher->profqua_other])) ?: '-' }}</span></div>
                <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block text-xs font-semibold mb-1.5">30. Professional Qualifications Possessed</span>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $profs = array_filter(explode('|', (string)$teacher->profall));
                        @endphp
                        @forelse($profs as $p)
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-xs font-semibold">{{ $p }}</span>
                        @empty
                            <span class="text-slate-400 text-xs italic">None recorded</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 05: Service --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">05</span>
                <h3 class="text-base font-bold text-slate-800">Service</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 text-sm">
                <div><span class="text-slate-400 block text-xs font-semibold">19. Current Service</span><span class="font-bold text-slate-800">{{ $teacher->service ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">20. First Appointment Date</span><span class="font-bold text-slate-800">{{ $teacher->firstappoint?->format('Y-m-d') ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">21. Current Class / Grade</span><span class="font-bold text-slate-800">{{ $teacher->class_grade ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">22. Promotion Date to Class</span><span class="font-bold text-slate-800">{{ $teacher->clsappoint?->format('Y-m-d') ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">31. Appointed Date to this School</span><span class="font-bold text-slate-800">{{ $teacher->schappoint?->format('Y-m-d') ?: '-' }}</span></div>
            </div>
        </div>

        {{-- Section 06: Duties & Subjects --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">06</span>
                <h3 class="text-base font-bold text-slate-800">Duties & Subjects</h3>
            </div>
            <div class="space-y-4 text-sm">
                <div>
                    <span class="text-slate-400 block text-xs font-semibold">23. Appointment Subject</span>
                    <span class="font-bold text-slate-800">
                        {{ implode(' | ', array_filter([$teacher->appt_section, $teacher->appt_category, $teacher->appt_code, $teacher->appt_medium])) ?: '-' }}
                    </span>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">24. First Most Work Involved</span>
                        <span class="font-bold text-slate-800">{{ implode(' - ', array_filter([$teacher->mainduty, $teacher->mainduty_other])) ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">26. Second Most Work Involved</span>
                        <span class="font-bold text-slate-800">{{ implode(' - ', array_filter([$teacher->secondduty, $teacher->secondduty_other])) ?: '-' }}</span>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">25. First Most Teaching Subject</span>
                        <span class="font-bold text-slate-800">
                            {{ implode(' | ', array_filter([$teacher->t1_section, $teacher->t1_category, $teacher->t1_code, $teacher->t1_medium, $teacher->t1_periods ? ($teacher->t1_periods . ' periods') : null])) ?: '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">27. Second Most Teaching Subject</span>
                        <span class="font-bold text-slate-800">
                            {{ implode(' | ', array_filter([$teacher->t2_section, $teacher->t2_category, $teacher->t2_code, $teacher->t2_medium, $teacher->t2_periods ? ($teacher->t2_periods . ' periods') : null])) ?: '-' }}
                        </span>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">28. Capable Subject 1</span>
                        <span class="font-bold text-slate-800">
                            {{ implode(' | ', array_filter([$teacher->able1_section, $teacher->able1_category, $teacher->able1_code, $teacher->able1_medium])) ?: '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">29. Capable Subject 2</span>
                        <span class="font-bold text-slate-800">
                            {{ implode(' | ', array_filter([$teacher->able2_section, $teacher->able2_category, $teacher->able2_code, $teacher->able2_medium])) ?: '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 07: Attachment to Another School/Office/Institute --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">07</span>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Attachment to Another School/Office/Institute</h3>
                    <p class="text-xs text-slate-500 font-medium">Service sharing across Schools/Offices/Institutes in the Wilgamuwa Zone</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-3 gap-x-6 text-sm">
                <div><span class="text-slate-400 block text-xs font-semibold">Attached to School / Office / Institute</span><span class="font-bold text-slate-800">{{ $teacher->otherschool ?: 'No' }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">Attached Institution</span><span class="font-bold text-slate-800">{{ $otherSchoolName }}</span></div>
                <div><span class="text-slate-400 block text-xs font-semibold">Days Worked</span><span class="font-bold text-slate-800">{{ implode(', ', array_filter(explode('|', (string)$teacher->otherschool_days))) ?: '-' }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('js/teacherPdfHelper.js') }}"></script>
<script>
    const teacherData = {
        census_no: @json($teacher->census_no),
        school_name: @json($schoolName),
        nic: @json($teacher->nic),
        paysheet: @json($teacher->paysheet),
        personal_file_no: @json($teacher->personal_file_no),
        title: @json($teacher->title),
        full_name: @json($teacher->fullName),
        date_of_birth: @json($teacher->dateOfBirth?->format('Y-m-d')),
        mobile: @json($teacher->phone),
        phone: @json($teacher->phone),
        whatsapp: @json($teacher->whatsapp),
        email: @json($teacher->email),
        address: @json($teacher->address),
        position: @json($teacher->position),
        salaryby: @json($teacher->salaryby),
        servicebased: @json($teacher->servicebased),
        salarysource: @json($teacher->salarysource),
        gender: @json($teacher->gender),
        ethnicity: @json($teacher->ethnicity),
        ethnicity_other: @json($teacher->ethnicity_other),
        religion: @json($teacher->religion),
        religion_other: @json($teacher->religion_other),
        marital: @json($teacher->maritalStatus),
        marital_other: @json($teacher->marital_other),
        eduqua: @json($teacher->eduqua),
        subject: @json($teacher->subject_stream),
        field: @json($teacher->field_study),
        field_other: @json($teacher->field_study_other),
        profqua: @json($teacher->profqua),
        profqua_other: @json($teacher->profqua_other),
        profall: @json($teacher->profall),
        service: @json($teacher->service),
        firstappoint: @json($teacher->firstappoint?->format('Y-m-d')),
        class: @json($teacher->class_grade),
        clsappoint: @json($teacher->clsappoint?->format('Y-m-d')),
        schappoint: @json($teacher->schappoint?->format('Y-m-d')),
        appt_section: @json($teacher->appt_section),
        appt_category: @json($teacher->appt_category),
        appt_code: @json($teacher->appt_code),
        appt_medium: @json($teacher->appt_medium),
        mainduty: @json($teacher->mainduty),
        mainduty_other: @json($teacher->mainduty_other),
        secondduty: @json($teacher->secondduty),
        secondduty_other: @json($teacher->secondduty_other),
        t1_section: @json($teacher->t1_section),
        t1_category: @json($teacher->t1_category),
        t1_code: @json($teacher->t1_code),
        t1_medium: @json($teacher->t1_medium),
        t1_periods: @json($teacher->t1_periods),
        t2_section: @json($teacher->t2_section),
        t2_category: @json($teacher->t2_category),
        t2_code: @json($teacher->t2_code),
        t2_medium: @json($teacher->t2_medium),
        t2_periods: @json($teacher->t2_periods),
        able1_section: @json($teacher->able1_section),
        able1_category: @json($teacher->able1_category),
        able1_code: @json($teacher->able1_code),
        able1_medium: @json($teacher->able1_medium),
        able2_section: @json($teacher->able2_section),
        able2_category: @json($teacher->able2_category),
        able2_code: @json($teacher->able2_code),
        able2_medium: @json($teacher->able2_medium),
        otherschool: @json($teacher->otherschool),
        otherschool_census: @json($teacher->otherschool_census),
        otherschool_name: @json($otherSchoolName),
        otherschool_days: @json($teacher->otherschool_days),
    };

    document.getElementById('show-preview-pdf-btn').addEventListener('click', function() {
        previewTeacherPdf(teacherData, @json($schoolName), @json($otherSchoolName));
    });

    document.getElementById('show-download-pdf-btn').addEventListener('click', function() {
        downloadTeacherPdf(teacherData, @json($schoolName), @json($otherSchoolName));
    });
</script>
@endpush
