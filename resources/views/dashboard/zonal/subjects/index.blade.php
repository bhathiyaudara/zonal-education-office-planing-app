@extends('layouts.dashboard')
@section('title', 'Subjects')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="subjectManager">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Manage Subjects</h1>
                <p class="zems-header-subtitle">Add and manage subject definitions</p>
            </div>
        </div>
        <button @click="openAddModal()"
                class="zems-btn-primary !bg-white !text-blue-700 hover:!bg-blue-50">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Subject
        </button>
    </div>

    <div class="zems-card-base p-5">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search subjects..." class="zems-input pl-12 h-[46px]">
            </div>
            <button class="zems-btn-primary h-[46px] w-full sm:w-auto uppercase tracking-wide px-6">Search</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subjects as $subject)
        <div class="zems-card-base hover:border-blue-300 hover:shadow-lg transition-all group h-full"
             :class="editingId === '{{ $subject->id }}' ? 'ring-2 ring-blue-400 border-blue-300 shadow-lg' : ''">

            {{-- View Mode --}}
            <div x-show="editingId !== '{{ $subject->id }}'" class="p-6">
                <div class="flex items-start justify-between h-full flex-col">
                    <div class="w-full relative">
                        <div class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 flex items-center gap-1 z-10 transition-all">
                            <button @click="startEdit('{{ $subject->id }}', '{{ addslashes($subject->name) }}', '{{ addslashes($subject->nameSinhala ?? '') }}', '{{ addslashes($subject->category ?? '') }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-600 hover:text-white transition-all text-sm shadow-sm" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button @click="openDeleteModal('{{ route('zonal.subjects.destroy', $subject->id) }}', '{{ addslashes($subject->name) }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition-all text-sm shadow-sm" title="Delete">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900 pr-20">{{ $subject->name }}</h3>
                        @if($subject->nameSinhala)
                        <p class="text-sm font-medium text-slate-500 mt-1">{{ $subject->nameSinhala }}</p>
                        @endif
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 w-full flex align-end justify-between items-center">
                        @if($subject->category)
                        <span class="zems-badge-primary bg-blue-50 text-blue-700 border border-blue-200">{{ $subject->category }}</span>
                        @else
                        <span class="zems-badge-secondary bg-slate-100 text-slate-600 border border-slate-200">Uncategorized</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Edit Mode --}}
            <div x-show="editingId === '{{ $subject->id }}'" x-cloak class="p-5">
                <form method="POST" action="{{ route('zonal.subjects.update', $subject->id) }}" @submit.prevent="submitEdit($event)">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <span class="text-sm font-bold text-blue-700 uppercase tracking-wide">Editing Subject</span>
                        </div>

                        <div>
                            <label class="zems-input-label text-xs">Subject Name (English) *</label>
                            <input type="text" name="name" x-model="editForm.name" required
                                   class="zems-input text-sm h-10 focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="zems-input-label text-xs">Subject Name (Sinhala)</label>
                            <input type="text" name="nameSinhala" x-model="editForm.nameSinhala"
                                   class="zems-input text-sm h-10 focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="zems-input-label text-xs">Category</label>
                            <select name="category" x-model="editForm.category"
                                    class="zems-input text-sm h-10 cursor-pointer focus:ring-2 focus:ring-blue-300">
                                <option value="">Select...</option>
                                <option value="Primary">Primary</option>
                                <option value="Junior">Junior</option>
                                <option value="Senior">Senior</option>
                                <option value="Advanced Level">Advanced Level</option>
                                <option value="Vocational">Vocational</option>
                            </select>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="cancelEdit()"
                                    class="zems-btn-secondary flex-1 justify-center text-sm py-2 text-slate-600">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Cancel
                            </button>
                            <button type="submit" class="zems-btn-primary flex-1 justify-center text-sm py-2"
                                    :disabled="saving" :class="saving ? 'opacity-75 cursor-wait' : ''">
                                <template x-if="!saving">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Save
                                    </span>
                                </template>
                                <template x-if="saving">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                        Saving...
                                    </span>
                                </template>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-slate-400 zems-card-base border-2 border-dashed border-slate-200">
            <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <p class="text-lg font-bold text-slate-600">No subjects found.</p>
            <p class="text-sm">Add a subject to get started.</p>
        </div>
        @endforelse
    </div>

    {{-- Add Subject Modal --}}
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;" x-cloak>
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10" @click="closeAddModal()"></div>
        <div class="zems-card-base w-full max-w-md animate-scale-in flex flex-col overflow-hidden relative z-20">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50 shrink-0">
                <h2 class="text-lg font-bold text-slate-900">Add Subject</h2>
                <button @click="closeAddModal()" class="p-1.5 hover:bg-slate-200 rounded-full transition-colors text-slate-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('zonal.subjects.store') }}" class="p-6 space-y-5 overflow-y-auto">
                @csrf
                <div>
                    <label class="zems-input-label">Subject Name (English) *</label>
                    <input type="text" name="name" required class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Subject Name (Sinhala)</label>
                    <input type="text" name="nameSinhala" class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Category</label>
                    <select name="category" class="zems-input cursor-pointer">
                        <option value="">Select...</option>
                        <option>Primary</option>
                        <option>Junior</option>
                        <option>Senior</option>
                        <option>Advanced Level</option>
                        <option>Vocational</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button type="submit" class="zems-btn-primary w-full justify-center py-2.5">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;" x-cloak>
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm -z-10"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-scale-in text-center relative z-20">
            <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Delete Subject?</h3>
            <p class="text-sm text-slate-500 mt-2">Delete <span x-text="deleteItemName" class="font-bold text-slate-700"></span>?</p>
            <div class="flex gap-3 mt-6">
                <button @click="closeDeleteModal()" class="zems-btn-secondary flex-1 text-slate-600">Cancel</button>
                <button @click="submitDelete()" class="zems-btn-primary !bg-red-600 hover:!bg-red-700 flex-1">Delete</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('subjectManager', () => ({
        // Add modal
        showAddModal: false,

        // Edit state
        editingId: null,
        editForm: { name: '', nameSinhala: '', category: '' },
        saving: false,

        // Delete modal
        showDeleteModal: false,
        deleteAction: '',
        deleteItemName: '',

        // Add modal methods
        openAddModal() { this.showAddModal = true; },
        closeAddModal() { this.showAddModal = false; },

        // Edit methods
        startEdit(id, name, nameSinhala, category) {
            this.editingId = id;
            this.editForm = {
                name: name,
                nameSinhala: nameSinhala || '',
                category: category || ''
            };
        },

        cancelEdit() {
            this.editingId = null;
            this.editForm = { name: '', nameSinhala: '', category: '' };
        },

        submitEdit(event) {
            this.saving = true;
            event.target.submit();
        },

        // Delete methods
        openDeleteModal(action, name) {
            this.deleteAction = action;
            this.deleteItemName = name;
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
        },

        submitDelete() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.deleteAction;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }));
});
</script>
@endpush
@endsection
