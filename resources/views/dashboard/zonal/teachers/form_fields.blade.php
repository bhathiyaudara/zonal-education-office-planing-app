@php
    $t = $teacher ?? null;
    $v = function($key, $default = '') use ($t) {
        if (old($key) !== null) return old($key);
        if (!$t) return $default;
        // Direct property
        if (isset($t->{$key}) && $t->{$key} !== null) {
            if ($t->{$key} instanceof \Carbon\Carbon || $t->{$key} instanceof \DateTimeInterface) {
                return $t->{$key}->format('Y-m-d');
            }
            return $t->{$key};
        }
        // Check form_data json
        if (!empty($t->form_data) && isset($t->form_data[$key])) {
            return $t->form_data[$key];
        }
        // Aliases
        if ($key === 'full_name') return $t->fullName;
        if ($key === 'date_of_birth' && $t->dateOfBirth) return $t->dateOfBirth->format('Y-m-d');
        if ($key === 'mobile') return $t->phone;
        if ($key === 'marital') return $t->maritalStatus;
        if ($key === 'class') return $t->class_grade;
        if ($key === 'subject') return $t->subject_stream;
        if ($key === 'field') return $t->field_study;
        if ($key === 'field_other') return $t->field_study_other;
        return $default;
    };

    $profallSelected = function($opt) use ($t, $v) {
        $val = $v('profall');
        if (is_array($val)) return in_array($opt, $val);
        $parts = array_filter(explode('|', (string)$val));
        return in_array($opt, $parts);
    };

    $daysSelected = function($day) use ($t, $v) {
        $val = $v('otherschool_days');
        if (is_array($val)) return in_array($day, $val);
        $parts = array_filter(explode('|', (string)$val));
        return in_array($day, $parts);
    };
@endphp

<div class="space-y-8" id="teacher-form-container">
    {{-- Section 01: School & Identity --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-sm">
        <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white shadow-md shadow-blue-500/20">01</span>
            <div>
                <h2 class="text-xl font-black text-slate-800">School & Identity</h2>
                <p class="text-xs text-slate-500">School assignment, personal identification and contact details</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- 01-02. School --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">01–02. School (Census No. & Name) <span class="text-rose-500">*</span></label>
                <select name="census_no" id="census_no_select" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select School —</option>
                    @foreach($options['schools'] ?? [] as $s)
                        <option value="{{ $s['censusNo'] }}" {{ $v('census_no') == $s['censusNo'] ? 'selected' : '' }}>
                            {{ $s['name'] }} ({{ $s['censusNo'] }})
                        </option>
                    @endforeach
                </select>
                @error('census_no') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Dynamic School Info Card --}}
            <div id="school-info-card" class="sm:col-span-2 hidden rounded-xl border border-blue-100 bg-blue-50/40 p-4 transition-all">
                <div class="flex items-center gap-2 mb-2 text-xs font-bold uppercase tracking-wider text-blue-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>School Details Overview</span>
                </div>
                <div id="school-info-grid" class="grid gap-x-6 gap-y-1.5 text-xs text-slate-700 sm:grid-cols-3"></div>
            </div>

            {{-- 03. NIC --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">03. National Identity Card No <span class="text-rose-500">*</span></label>
                <input type="text" name="nic" id="field_nic" value="{{ $v('nic') }}" required placeholder="e.g. 948232790V or 199570503360" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 uppercase transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                @error('nic') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- 04. Pay Sheet / Emp No --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">04. Pay Sheet / Emp No</label>
                <input type="text" name="paysheet" value="{{ $v('paysheet') }}" placeholder="e.g. PS-10492" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 05. Title --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">05. Title</label>
                <select name="title" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q05title'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('title') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Personal File Number --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Personal File Number</label>
                <input type="text" name="personal_file_no" value="{{ $v('personal_file_no') }}" placeholder="e.g. PF/WLG/482" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 06. Name as the Paysheet --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">06. Name as the Paysheet (in English) <span class="text-rose-500">*</span></label>
                <input type="text" name="full_name" id="field_full_name" value="{{ $v('full_name') }}" required placeholder="e.g. Siriwardena KDS / G.M. Herath" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                @error('full_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Date of Birth --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Date of Birth <span class="text-rose-500">*</span></label>
                <input type="date" name="date_of_birth" value="{{ $v('date_of_birth') }}" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                @error('date_of_birth') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- 32. Mobile Phone No --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">32. Mobile Phone No</label>
                <input type="text" name="mobile" value="{{ $v('mobile') }}" placeholder="07XXXXXXXX" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- WhatsApp Number --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">WhatsApp Number</label>
                <input type="text" name="whatsapp" value="{{ $v('whatsapp') }}" placeholder="07XXXXXXXX" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- Email Address --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ $v('email') }}" placeholder="name@example.com" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Address --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Permanent / Residential Address</label>
                <textarea name="address" rows="3" placeholder="Enter full address" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">{{ $v('address') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Section 02: Position & Salary --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-sm">
        <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white shadow-md shadow-blue-500/20">02</span>
            <div>
                <h2 class="text-xl font-black text-slate-800">Position & Salary</h2>
                <p class="text-xs text-slate-500">School designation, salary provider and funding source</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- 07. Position --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">07. Position Held in this School</label>
                <select name="position" id="position_select" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q07position'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('position') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <div id="principal_assigned_badge" class="hidden mt-2 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span><strong>Principal Auto-Assignment:</strong> Selecting Principal will automatically assign this person as Principal of the related school with School Administrator access.</span>
                </div>
            </div>

            {{-- 08. Salary By --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">08. Salary Paid By</label>
                <select name="salaryby" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q08salaryby'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('salaryby') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 09. Service Based --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">09. Service Base of this School (Govt. Salary only)</label>
                <select name="servicebased" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q09servicebased'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('servicebased') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 10. Salary Source --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">10. Source of Salary Paid (Non-Govt. Salary only)</label>
                <select name="salarysource" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q10salarysource'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('salarysource') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Section 03: Personal Details --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-sm">
        <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white shadow-md shadow-blue-500/20">03</span>
            <div>
                <h2 class="text-xl font-black text-slate-800">Personal Details</h2>
                <p class="text-xs text-slate-500">Gender, ethnicity, religion and marital status</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- 11. Gender --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">11. Gender</label>
                <select name="gender" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q11gender'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('gender') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 12. Ethnicity --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">12. Ethnicity</label>
                <select name="ethnicity" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q12ethnicity'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('ethnicity') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Ethnicity Other --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Ethnicity (Specify if Other)</label>
                <input type="text" name="ethnicity_other" value="{{ $v('ethnicity_other') }}" placeholder="Specify ethnicity" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 13. Religion --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">13. Religion</label>
                <select name="religion" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q13religion'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('religion') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Religion Other --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Religion (Specify if Other)</label>
                <input type="text" name="religion_other" value="{{ $v('religion_other') }}" placeholder="Specify religion" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 14. Marital Status --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">14. Current Marital Status</label>
                <select name="marital" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q14marital'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('marital') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Marital Status Other --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Marital Status (Specify if Other)</label>
                <input type="text" name="marital_other" value="{{ $v('marital_other') }}" placeholder="Specify marital status" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>
        </div>
    </div>

    {{-- Section 04: Qualifications --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-sm">
        <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white shadow-md shadow-blue-500/20">04</span>
            <div>
                <h2 class="text-xl font-black text-slate-800">Qualifications</h2>
                <p class="text-xs text-slate-500">Highest educational degrees, A/L stream, and professional qualifications</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- 15. Highest Education Qualification --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">15. Highest Education Qualification</label>
                <select name="eduqua" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q15eduqua'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('eduqua') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 16. Subject Stream studied in A/L --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">16. Subject Stream studied in G.C.E. (A/L)</label>
                <select name="subject" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q16subject'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('subject') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 17. Field of Study in Basic Degree --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">17. Field of Study in Bachelor's Degree</label>
                <select name="field" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q17field'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('field') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Field Other --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Field of Study (Specify if Other)</label>
                <input type="text" name="field_other" value="{{ $v('field_other') }}" placeholder="Specify field of study" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 18. Category of Highest Professional Qualification --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">18. Highest Professional Qualification</label>
                <select name="profqua" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q18profqua'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('profqua') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Profqua Other --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Professional Qualification (Specify if Other)</label>
                <input type="text" name="profqua_other" value="{{ $v('profqua_other') }}" placeholder="Specify professional qualification" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 30. Professional Qualifications Possessed (Tick all) --}}
            <div class="sm:col-span-2 rounded-xl bg-slate-50 p-4 border border-slate-200/80">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-3">30. Professional Qualifications You Possess (Tick all that apply)</span>
                <div class="grid gap-2.5 sm:grid-cols-2">
                    @foreach($options['q30op1'] ?? [] as $q)
                        <label class="flex items-start gap-2.5 text-xs font-medium text-slate-700 cursor-pointer hover:text-slate-900">
                            <input type="checkbox" name="profall[]" value="{{ $q }}" {{ $profallSelected($q) ? 'checked' : '' }} class="mt-0.5 rounded text-blue-600 focus:ring-blue-500">
                            <span>{{ $q }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Section 05: Service --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-sm">
        <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white shadow-md shadow-blue-500/20">05</span>
            <div>
                <h2 class="text-xl font-black text-slate-800">Service</h2>
                <p class="text-xs text-slate-500">Current service, appointment dates, and class/grade milestones</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- 19. Current Service --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">19. Current Service</label>
                <select name="service" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q19service'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('service') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 20. First Appointment Date --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">20. First Appointment Date to Current Service</label>
                <input type="date" name="firstappoint" value="{{ $v('firstappoint') }}" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 21. Current Class / Grade --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">21. Current Class / Grade</label>
                <select name="class" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    @foreach($options['q21class'] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ $v('class') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 22. Promotion Date to Class --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">22. Promotion Date to Current Class / Grade</label>
                <input type="date" name="clsappoint" value="{{ $v('clsappoint') }}" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>

            {{-- 31. Appointed Date to this School --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">31. Appointed / Assigned Date to this School</label>
                <input type="date" name="schappoint" value="{{ $v('schappoint') }}" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            </div>
        </div>
    </div>

    {{-- Section 06: Duties & Subjects --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-sm">
        <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white shadow-md shadow-blue-500/20">06</span>
            <div>
                <h2 class="text-xl font-black text-slate-800">Duties & Subjects</h2>
                <p class="text-xs text-slate-500">Appointment subjects, teaching loads, and capable secondary subjects</p>
            </div>
        </div>

        <div class="space-y-6">
            {{-- 23. Appointment Subject --}}
            <div class="rounded-xl bg-slate-50/80 p-5 border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-xs font-bold text-blue-700">23</span>
                    Appointment Subject (SLTS Teachers only)
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Section (A)</label>
                        <select name="appt_section" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23section'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('appt_section') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject Category (B)</label>
                        <select name="appt_category" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23category'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('appt_category') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject & Code (C/D)</label>
                        <select name="appt_code" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23code'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('appt_code') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Medium (E)</label>
                        <select name="appt_medium" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23medium'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('appt_medium') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 24 & 26 Duties Involved --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">24. First Most Work Involved</label>
                    <select name="mainduty" id="mainduty_select" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                        <option value="">— Select —</option>
                        @foreach($options['q24fmostwork'] ?? [] as $opt)
                            <option value="{{ $opt }}" {{ $v('mainduty') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <div id="q24_attached_hint" class="hidden mt-2 rounded-xl bg-blue-50 border border-blue-200 p-2.5 text-xs text-blue-800 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Marked as attached/released — please specify destination in <strong>Section 07</strong> below.</span>
                        </div>
                        <a href="#section_attachment" class="ml-2 font-bold text-blue-700 underline hover:text-blue-900 shrink-0">Section 07 &darr;</a>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">First Most Work (Specify if Other)</label>
                    <input type="text" name="mainduty_other" value="{{ $v('mainduty_other') }}" placeholder="Specify other duty" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">26. Second Most Work Involved</label>
                    <select name="secondduty" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                        <option value="">— Select —</option>
                        @foreach($options['q26smostwork'] ?? [] as $opt)
                            <option value="{{ $opt }}" {{ $v('secondduty') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Second Most Work (Specify if Other)</label>
                    <input type="text" name="secondduty_other" value="{{ $v('secondduty_other') }}" placeholder="Specify other duty" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                </div>
            </div>

            {{-- 25. First Most Teaching Subject --}}
            <div class="rounded-xl bg-slate-50/80 p-5 border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-700">25</span>
                    First Most Teaching Subject
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Section (A)</label>
                        <select name="t1_section" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23section'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t1_section') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject Category (B)</label>
                        <select name="t1_category" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23category'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t1_category') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject & Code (C/D)</label>
                        <select name="t1_code" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23code'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t1_code') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Medium (E)</label>
                        <select name="t1_medium" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23medium'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t1_medium') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Periods (F)</label>
                        <input type="number" name="t1_periods" value="{{ $v('t1_periods') }}" placeholder="Number of periods per week" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- 27. Second Most Teaching Subject --}}
            <div class="rounded-xl bg-slate-50/80 p-5 border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-700">27</span>
                    Second Most Teaching Subject
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Section (A)</label>
                        <select name="t2_section" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23section'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t2_section') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject Category (B)</label>
                        <select name="t2_category" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23category'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t2_category') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject & Code (C/D)</label>
                        <select name="t2_code" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23code'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t2_code') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Medium (E)</label>
                        <select name="t2_medium" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23medium'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('t2_medium') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Periods (F)</label>
                        <input type="number" name="t2_periods" value="{{ $v('t2_periods') }}" placeholder="Number of periods per week" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- 28. Capable Subject 1 --}}
            <div class="rounded-xl bg-slate-50/80 p-5 border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-xs font-bold text-amber-700">28</span>
                    Capable Subject 1 (Except appointment & teaching subjects)
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Section (A)</label>
                        <select name="able1_section" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23section'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able1_section') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject Category (B)</label>
                        <select name="able1_category" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23category'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able1_category') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject & Code (C/D)</label>
                        <select name="able1_code" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23code'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able1_code') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Medium (E)</label>
                        <select name="able1_medium" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23medium'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able1_medium') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 29. Capable Subject 2 --}}
            <div class="rounded-xl bg-slate-50/80 p-5 border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-xs font-bold text-amber-700">29</span>
                    Capable Subject 2 (Except appointment & teaching subjects)
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Section (A)</label>
                        <select name="able2_section" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23section'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able2_section') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject Category (B)</label>
                        <select name="able2_category" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23category'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able2_category') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subject & Code (C/D)</label>
                        <select name="able2_code" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23code'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able2_code') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Medium (E)</label>
                        <select name="able2_medium" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                            <option value="">— Select —</option>
                            @foreach($options['q23medium'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $v('able2_medium') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 07: Attachment to Another School/Office/Institute --}}
    <div id="section_attachment" class="rounded-2xl border border-slate-200/80 bg-white p-6 md:p-8 shadow-sm transition-all duration-300">
        <div class="mb-6 flex items-start justify-between border-b border-slate-100 pb-4 gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white shadow-md shadow-blue-500/20">07</span>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Attachment to Another School/Office/Institute</h2>
                    <p class="text-xs text-slate-500 font-medium">(Service sharing across Schools/Offices/Institutes in the Wilgamuwa Zone)</p>
                </div>
            </div>
            <div id="q24_attached_badge" class="hidden items-center gap-1.5 rounded-xl bg-amber-50 border border-amber-300 px-3 py-1.5 text-xs font-bold text-amber-800">
                <svg class="h-4 w-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Required by Q24 (91 - Attached/released to other Office/Institute)</span>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Is this teacher attached/released to another school, office, or institute?</label>
                <select name="otherschool" id="otherschool_select" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="">— Select —</option>
                    <option value="No" {{ $v('otherschool') == 'No' ? 'selected' : '' }}>No</option>
                    <option value="Yes — full time at another school/office/institute" {{ (str_starts_with((string)$v('otherschool'), 'Yes — full time') || $v('otherschool') == 'Yes') ? 'selected' : '' }}>Yes — full time at another school/office/institute</option>
                    <option value="Yes — on specific days of the week" {{ str_starts_with((string)$v('otherschool'), 'Yes — on specific days') ? 'selected' : '' }}>Yes — on specific days of the week</option>
                </select>
            </div>

            <div id="otherschool_details" class="sm:col-span-2 space-y-4 rounded-xl bg-slate-50 p-5 border border-slate-200 {{ str_starts_with((string)$v('otherschool'), 'Yes') ? '' : 'hidden' }}">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Which School / Educational Office / Institute is the teacher attached to? <span class="text-rose-500">*</span></label>
                    <select name="otherschool_census" id="otherschool_census_select" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none">
                        <option value="">— Select School, Educational Office or Institute —</option>
                        
                        <optgroup label="Educational Offices / Institutes in Wilgamuwa Zone">
                            <option value="OFFICE_ZEO" {{ $v('otherschool_census') == 'OFFICE_ZEO' ? 'selected' : '' }}>
                                Zonal Education Office - Wilgamuwa
                            </option>
                            <option value="OFFICE_PDC" {{ $v('otherschool_census') == 'OFFICE_PDC' ? 'selected' : '' }}>
                                Professional Development Centre for Teachers (PDC)
                            </option>
                            <option value="OFFICE_DEO_WIL" {{ $v('otherschool_census') == 'OFFICE_DEO_WIL' ? 'selected' : '' }}>
                                Divisional Education Office - Wilgamuwa
                            </option>
                            <option value="OFFICE_DEO_PAL" {{ $v('otherschool_census') == 'OFFICE_DEO_PAL' ? 'selected' : '' }}>
                                Divisional Education Office - Pallegama
                            </option>
                            <option value="OFFICE_ITDLH" {{ $v('otherschool_census') == 'OFFICE_ITDLH' ? 'selected' : '' }}>
                                Information Technology and Distance Learning Hub (ITDLH)
                            </option>
                        </optgroup>

                        <optgroup label="Schools in Wilgamuwa Zone">
                            @foreach($options['schools'] ?? [] as $s)
                                <option value="{{ $s['censusNo'] }}" {{ $v('otherschool_census') == $s['censusNo'] ? 'selected' : '' }}>
                                    {{ $s['name'] }} ({{ $s['censusNo'] }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                {{-- Attached Destination Overview Card --}}
                <div id="otherschool-info-card" class="hidden rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex items-center gap-2 mb-2 text-xs font-bold uppercase tracking-wider text-slate-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Attached Destination Overview</span>
                    </div>
                    <div id="otherschool-info-grid" class="grid gap-x-6 gap-y-1.5 text-xs text-slate-700 sm:grid-cols-3"></div>
                </div>

                <div id="otherschool_days_box" class="{{ str_starts_with((string)$v('otherschool'), 'Yes — on specific days') ? '' : 'hidden' }}">
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Days Worked at the Other School/Office/Institute</span>
                    <div class="grid gap-2 sm:grid-cols-4">
                        @foreach(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"] as $d)
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-700">
                                <input type="checkbox" name="otherschool_days[]" value="{{ $d }}" {{ $daysSelected($d) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                <span>{{ $d }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Certification --}}
    <div class="rounded-xl border border-slate-200/90 bg-white p-5 shadow-sm">
        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="certify" value="yes" required checked class="mt-1 h-4 w-4 rounded text-blue-600 focus:ring-blue-500">
            <span class="text-sm font-semibold text-slate-700">
                I certify that the above information is true and accurate. <span class="text-rose-500">*</span>
            </span>
        </label>
        @error('certify') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Pass options to Javascript --}}
<script>
    window.TEACHER_OPTIONS = @json($options ?? []);
    
    document.addEventListener('DOMContentLoaded', function() {
        const censusSelect = document.getElementById('census_no_select');
        const infoCard = document.getElementById('school-info-card');
        const infoGrid = document.getElementById('school-info-grid');

        function updateSchoolCard(census, card, grid) {
            if (!census || !window.TEACHER_OPTIONS.schools) {
                card.classList.add('hidden');
                return;
            }
            const school = window.TEACHER_OPTIONS.schools.find(s => s.censusNo === census);
            if (school && school.info) {
                grid.innerHTML = Object.entries(school.info).map(([k, val]) => 
                    `<div><span class="text-slate-400 font-semibold">${k}:</span> <span class="font-medium text-slate-800">${val || '—'}</span></div>`
                ).join('');
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        }

        if (censusSelect) {
            censusSelect.addEventListener('change', () => updateSchoolCard(censusSelect.value, infoCard, infoGrid));
            if (censusSelect.value) {
                updateSchoolCard(censusSelect.value, infoCard, infoGrid);
            }
        }

        // Destination overview (schools & offices)
        const educationalOffices = {
            'OFFICE_ZEO': { name: 'Zonal Education Office - Wilgamuwa', type: 'Zonal Educational Administration', location: 'Wilgamuwa' },
            'OFFICE_PDC': { name: 'Professional Development Centre for Teachers (PDC)', type: 'Teacher Training & Professional Development', location: 'Wilgamuwa Zone' },
            'OFFICE_DEO_WIL': { name: 'Divisional Education Office - Wilgamuwa', type: 'Divisional Educational Administration', location: 'Wilgamuwa' },
            'OFFICE_DEO_PAL': { name: 'Divisional Education Office - Pallegama', type: 'Divisional Educational Administration', location: 'Pallegama' },
            'OFFICE_ITDLH': { name: 'Information Technology and Distance Learning Hub (ITDLH)', type: 'ICT Education & Distance Learning', location: 'Wilgamuwa Zone' }
        };

        function updateDestinationCard(census, card, grid) {
            if (!census) {
                card.classList.add('hidden');
                return;
            }
            if (educationalOffices[census]) {
                const off = educationalOffices[census];
                grid.innerHTML = `
                    <div><span class="text-slate-400 font-semibold">Destination:</span> <span class="font-bold text-blue-700">${off.name}</span></div>
                    <div><span class="text-slate-400 font-semibold">Type:</span> <span class="font-medium text-slate-800">${off.type}</span></div>
                    <div><span class="text-slate-400 font-semibold">Location:</span> <span class="font-medium text-slate-800">${off.location}</span></div>
                `;
                card.classList.remove('hidden');
                return;
            }
            updateSchoolCard(census, card, grid);
        }

        // Other school/office/institute
        const otherSelect = document.getElementById('otherschool_select');
        const otherBox = document.getElementById('otherschool_details');
        const otherDaysBox = document.getElementById('otherschool_days_box');
        const otherCensusSelect = document.getElementById('otherschool_census_select');
        const otherInfoCard = document.getElementById('otherschool-info-card');
        const otherInfoGrid = document.getElementById('otherschool-info-grid');

        if (otherSelect) {
            otherSelect.addEventListener('change', function() {
                const val = this.value || '';
                if (val.startsWith('Yes')) {
                    otherBox.classList.remove('hidden');
                    if (val.includes('specific days')) {
                        otherDaysBox.classList.remove('hidden');
                    } else {
                        otherDaysBox.classList.add('hidden');
                    }
                } else {
                    otherBox.classList.add('hidden');
                    otherDaysBox.classList.add('hidden');
                }
            });
        }

        if (otherCensusSelect) {
            otherCensusSelect.addEventListener('change', () => updateDestinationCard(otherCensusSelect.value, otherInfoCard, otherInfoGrid));
            if (otherCensusSelect.value) {
                updateDestinationCard(otherCensusSelect.value, otherInfoCard, otherInfoGrid);
            }
        }

        // Auto trigger Section 07 when Q24 is "91 - Attached/released to other Office/Institute"
        const mainDutySelect = document.getElementById('mainduty_select');
        const q24Hint = document.getElementById('q24_attached_hint');
        const q24Badge = document.getElementById('q24_attached_badge');
        const sectionAttachment = document.getElementById('section_attachment');

        function handleQ24Attachment(userTriggered = false) {
            if (!mainDutySelect) return;
            const val = (mainDutySelect.value || '').trim();
            const is91Attached = val.startsWith('91') || val.toLowerCase().includes('attached/released');

            if (is91Attached) {
                if (q24Hint) q24Hint.classList.remove('hidden');
                if (q24Badge) {
                    q24Badge.classList.remove('hidden');
                    q24Badge.classList.add('flex');
                }

                // If Section 07 attachment select is not already set to Yes, set it automatically
                if (otherSelect && (!otherSelect.value || otherSelect.value === 'No')) {
                    otherSelect.value = 'Yes — full time at another school/office/institute';
                }
                if (otherBox) {
                    otherBox.classList.remove('hidden');
                }

                if (userTriggered && sectionAttachment) {
                    sectionAttachment.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    sectionAttachment.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50/20');
                    setTimeout(() => {
                        sectionAttachment.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50/20');
                    }, 2500);
                    if (otherCensusSelect && !otherCensusSelect.value) {
                        otherCensusSelect.focus();
                    }
                }
            } else {
                if (q24Hint) q24Hint.classList.add('hidden');
                if (q24Badge) {
                    q24Badge.classList.add('hidden');
                    q24Badge.classList.remove('flex');
                }
            }
        }

        if (mainDutySelect) {
            mainDutySelect.addEventListener('change', () => handleQ24Attachment(true));
            // Check immediately on initial load
            handleQ24Attachment(false);
        }

        // Principal notice auto-check
        const posSelect = document.getElementById('position_select');
        const principalBadge = document.getElementById('principal_assigned_badge');
        if (posSelect && principalBadge) {
            function updatePrincipalNotice() {
                const val = (posSelect.value || '').trim();
                const isPrin = val.startsWith('1 - Principal') || val.startsWith('2 - Acting Principal') || (val.toLowerCase().includes('principal') && !val.toLowerCase().includes('deputy') && !val.toLowerCase().includes('assistant'));
                if (isPrin) {
                    principalBadge.classList.remove('hidden');
                } else {
                    principalBadge.classList.add('hidden');
                }
            }
            posSelect.addEventListener('change', updatePrincipalNotice);
            updatePrincipalNotice();
        }
    });
</script>
