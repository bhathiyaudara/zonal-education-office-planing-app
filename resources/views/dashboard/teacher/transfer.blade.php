@extends('layouts.dashboard')
@section('title', 'Transfer Request')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
            </div>
            <div>
                <h1 class="zems-header-title">Transfer Request</h1>
                <p class="zems-header-subtitle">Submit a request to transfer to another school</p>
            </div>
        </div>
    </div>

    @if(!$teacher)
    <div class="zems-card-base p-6 text-center border-dashed border-2">
        <p class="text-amber-600 font-bold">Teacher profile not found. Contact your school administrator.</p>
    </div>
    @else
    {{-- Request Form --}}
    <div class="zems-card-base p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-6">New Transfer Request</h2>
        <form method="POST" action="{{ route('teacher.transfer.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="zems-input-label">Transfer to School *</label>
                <select name="toSchoolId" required class="zems-input">
                    <option value="">Select a school...</option>
                    @foreach($schools as $school)
                        @if($school->id !== $teacher->schoolId)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label class="zems-input-label">Reason</label>
                <textarea name="reason" rows="3" class="zems-input" placeholder="Describe your reason for transfer..."></textarea>
            </div>
            <div class="pt-2 flex justify-end">
                <button type="submit" class="zems-btn-primary">
                    <svg class="w-4 h-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    {{-- Previous Requests --}}
    <div class="zems-card-base p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-6">My Transfer Requests</h2>
        <div class="space-y-4">
            @forelse($transfers as $tr)
            <div class="flex items-center justify-between p-5 rounded-xl border border-slate-200 hover:border-slate-300 transition-colors shadow-sm">
                <div>
                    <p class="font-bold text-slate-900">{{ $tr->fromSchool->name ?? '-' }} <span class="text-slate-400 mx-1">→</span> {{ $tr->toSchool->name ?? '-' }}</p>
                    <p class="text-xs font-semibold text-slate-400 mt-2 uppercase tracking-wider">{{ $tr->createdAt->format('M d, Y') }}</p>
                </div>
                <span class="zems-badge-secondary
                    {{ $tr->status === 'PENDING' ? '!bg-amber-100 !text-amber-700 !border-amber-200' : '' }}
                    {{ $tr->status === 'APPROVED' ? '!bg-emerald-100 !text-emerald-700 !border-emerald-200' : '' }}
                    {{ $tr->status === 'REJECTED' ? '!bg-red-100 !text-red-700 !border-red-200' : '' }}">
                    {{ $tr->status }}
                </span>
            </div>
            @empty
            <div class="py-12 text-center text-slate-400 border-dashed border-2 rounded-xl border-slate-200">
                <svg class="w-12 h-12 opacity-20 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <p class="text-sm font-black uppercase tracking-wider">No transfer requests yet.</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif
</div>
@endsection
