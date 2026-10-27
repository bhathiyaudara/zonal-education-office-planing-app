@extends('layouts.dashboard')

@section('title', 'Manage Students')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="schoolStudents()">
    {{-- Header --}}
    <div class="zems-header">
        <div class="flex items-center justify-between w-full flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="zems-header-title">Student Demographics ({{ date('Y') }})</h1>
                    <p class="zems-header-subtitle">Manage class counts and gender breakdown for {{ $school->name }}</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <template x-if="!isEditingStudents">
                    <button @click="isEditingStudents = true" class="zems-btn-header">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Counts
                    </button>
                </template>
                <template x-if="isEditingStudents">
                    <div class="flex gap-2">
                        <button @click="isEditingStudents = false; resetData()" class="zems-btn-secondary !bg-white/10 !text-white !border-white/20 hover:!bg-white/20 backdrop-blur-sm shadow-none">Cancel</button>
                        <button @click="saveStudentCounts" class="zems-btn-secondary !bg-emerald-500 !text-white !border-emerald-500 hover:!bg-emerald-600 backdrop-blur-sm shadow-sm border-none">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </template>
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

    {{-- Main Table Area --}}
    <div class="zems-table-container">
        <table class="zems-table">
            <thead>
                <tr class="zems-table-header-row">
                    <th class="zems-table-th">Grade</th>
                    <th class="zems-table-th">Classes & Counts</th>
                    <th class="zems-table-th text-center !text-blue-500 w-24">Total Male</th>
                    <th class="zems-table-th text-center !text-pink-500 w-24">Total Female</th>
                    <th class="zems-table-th text-center w-24">Total</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(grade, index) in grades" :key="grade">
                    <tr class="zems-table-row">
                        <td class="zems-table-td font-bold text-slate-900 w-28 align-top pt-4">
                            <span x-text="grade"></span>
                        </td>
                        <td class="zems-table-td align-top pt-4">
                            <template x-if="!isEditingStudents">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="cls in getStudentData(grade).breakdown">
                                        <div class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs shadow-sm flex items-center gap-3">
                                            <span class="font-black text-slate-700 font-mono" x-text="cls.name || 'Unnamed'"></span>
                                            <div class="flex items-center gap-2 text-[11px] font-bold">
                                                <span class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">M: <span x-text="cls.maleCount"></span></span>
                                                <span class="text-pink-600 bg-pink-50 px-1.5 py-0.5 rounded">F: <span x-text="cls.femaleCount"></span></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="getStudentData(grade).breakdown.length === 0">
                                        <span class="text-slate-400 text-xs italic py-1.5">No classes added</span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="isEditingStudents">
                                <div class="space-y-2">
                                    <template x-for="(cls, cIndex) in getStudentData(grade).breakdown" :key="cIndex">
                                        <div class="flex items-center gap-2 group">
                                            <div class="relative w-32">
                                                <span class="absolute left-2.5 top-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider pointer-events-none">Class</span>
                                                <input type="text" x-model="cls.name" @input="calculateTotal(grade)" class="zems-input py-1.5 pl-12 pr-2 text-xs shadow-sm bg-white font-bold text-slate-700" placeholder="e.g. A, Bio, 1">
                                            </div>
                                            <div class="relative w-24">
                                                <span class="absolute left-2.5 top-2 text-[10px] font-bold text-blue-400 uppercase tracking-wider pointer-events-none">Male</span>
                                                <input type="number" min="0" x-model.number="cls.maleCount" @input="calculateTotal(grade)" class="zems-input py-1.5 pl-12 pr-2 text-xs text-center shadow-sm bg-blue-50/50 text-blue-700 font-bold border-blue-100 placeholder:text-blue-300" placeholder="0">
                                            </div>
                                            <div class="relative w-24">
                                                <span class="absolute left-2.5 top-2 text-[10px] font-bold text-pink-400 uppercase tracking-wider pointer-events-none">Female</span>
                                                <input type="number" min="0" x-model.number="cls.femaleCount" @input="calculateTotal(grade)" class="zems-input py-1.5 pl-14 pr-2 text-xs text-center shadow-sm bg-pink-50/50 text-pink-700 font-bold border-pink-100 placeholder:text-pink-300" placeholder="0">
                                            </div>
                                            <button @click="removeClass(grade, cIndex)" type="button" class="text-slate-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors ml-1" title="Remove Class">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="addClass(grade)" type="button" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors border border-dashed border-indigo-200 mt-2">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add Class to <span x-text="grade"></span>
                                    </button>
                                </div>
                            </template>
                        </td>
                        <td class="zems-table-td text-center align-middle">
                            <span class="text-sm font-black text-blue-600" x-text="getStudentData(grade).maleCount || 0"></span>
                        </td>
                        <td class="zems-table-td text-center align-middle">
                            <span class="text-sm font-black text-pink-600" x-text="getStudentData(grade).femaleCount || 0"></span>
                        </td>
                        <td class="zems-table-td text-center align-middle">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 font-black rounded-xl text-sm shadow-sm" x-text="(parseInt(getStudentData(grade).maleCount) || 0) + (parseInt(getStudentData(grade).femaleCount) || 0)"></span>
                        </td>
                    </tr>
                </template>

                {{-- Overall Totals Row --}}
                <tr class="bg-gradient-to-r from-slate-50 to-white">
                    <td colspan="2" class="zems-table-td text-right text-xs tracking-widest uppercase font-black text-slate-500">SCHOOL TOTALS</td>
                    <td class="zems-table-td text-center text-lg font-black text-blue-600" x-text="grades.reduce((sum, g) => sum + (getStudentData(g).maleCount || 0), 0)"></td>
                    <td class="zems-table-td text-center text-lg font-black text-pink-600" x-text="grades.reduce((sum, g) => sum + (getStudentData(g).femaleCount || 0), 0)"></td>
                    <td class="zems-table-td text-center">
                        <span class="px-4 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md font-black rounded-xl text-lg" x-text="grades.reduce((sum, g) => sum + (getStudentData(g).maleCount || 0) + (getStudentData(g).femaleCount || 0), 0)"></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function schoolStudents() {
        return {
            isEditingStudents: false,
            // Grades strictly 1 to 13 as requested by user
            grades: [
                @for($i = 1; $i <= 13; $i++)
                    'Grade {{ $i }}',
                @endfor
            ],
            studentData: {},

            init() {
                const serverData = @json($school->studentCounts);
                
                this.grades.forEach(grade => {
                    const found = serverData.find(d => d.grade === grade);
                    
                    let breakdown = [];
                    if (found) {
                        try {
                            let parsedDetails = found.classDetails;
                            if (typeof parsedDetails === 'string' && parsedDetails.trim() !== '') {
                                parsedDetails = JSON.parse(parsedDetails);
                            }
                            if (Array.isArray(parsedDetails) && parsedDetails.length > 0) {
                                breakdown = parsedDetails.map(item => ({
                                    name: item.name || '',
                                    maleCount: item.maleCount || item.male || 0,
                                    femaleCount: item.femaleCount || item.female || 0
                                }));
                            }
                        } catch (e) {
                            console.error("Error parsing classDetails", e);
                        }

                        // If they had legacy data without a breakdown, migrate it into a single class "A"
                        if (breakdown.length === 0 && (found.maleCount > 0 || found.femaleCount > 0)) {
                            // Only 1 class
                            breakdown.push({
                                name: found.classes ? found.classes.split(',')[0].trim() : 'A',
                                maleCount: found.maleCount || 0,
                                femaleCount: found.femaleCount || 0
                            });
                        }
                    }

                    this.studentData[grade] = {
                        classes: found ? found.classes || '' : '',
                        maleCount: found ? found.maleCount || 0 : 0,
                        femaleCount: found ? found.femaleCount || 0 : 0,
                        breakdown: breakdown
                    };
                });
            },

            getStudentData(grade) {
                if (!this.studentData[grade]) {
                     this.studentData[grade] = { classes: '', maleCount: 0, femaleCount: 0, breakdown: [] };
                }
                return this.studentData[grade];
            },

            addClass(grade) {
                const data = this.studentData[grade];
                // Suggest next letter (A, B, C...)
                const nextChar = String.fromCharCode(65 + data.breakdown.length);
                data.breakdown.push({ name: nextChar, maleCount: 0, femaleCount: 0 });
                this.calculateTotal(grade);
            },

            removeClass(grade, index) {
                if (confirm('Are you sure you want to remove this class?')) {
                    const data = this.studentData[grade];
                    data.breakdown.splice(index, 1);
                    this.calculateTotal(grade);
                }
            },

            calculateTotal(grade) {
                this.$nextTick(() => {
                    const data = this.studentData[grade];
                    if (data.breakdown) {
                        data.classes = data.breakdown.map(c => c.name).filter(Boolean).join(', ');
                        data.maleCount = data.breakdown.reduce((sum, cls) => sum + (parseInt(cls.maleCount) || 0), 0);
                        data.femaleCount = data.breakdown.reduce((sum, cls) => sum + (parseInt(cls.femaleCount) || 0), 0);
                    }
                });
            },

            saveStudentCounts() {
                this.grades.forEach(grade => this.calculateTotal(grade));

                const countsArray = this.grades.map(grade => ({
                    grade: grade,
                    classCount: this.studentData[grade].breakdown.length,
                    classes: this.studentData[grade].classes,
                    maleCount: this.studentData[grade].maleCount,
                    femaleCount: this.studentData[grade].femaleCount,
                    classDetails: this.studentData[grade].breakdown.map(cls => ({
                        name: cls.name,
                        maleCount: cls.maleCount,
                        femaleCount: cls.femaleCount
                    }))
                }));

                fetch('{{ route("school.students.update") }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ counts: countsArray })
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        response.json().then(data => {
                            console.error('Validation errors:', data);
                            alert('Failed to save: ' + (data.message || 'Unknown error'));
                        }).catch(() => {
                            alert('Failed to save data. Please try again.');
                        });
                    }
                })
                .catch(err => {
                    console.error('Network error:', err);
                    alert('Network error. Please check your connection.');
                });
            },
            
            resetData() {
                this.init();
            }
        }
    }
</script>
@endsection
