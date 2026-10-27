@extends('layouts.dashboard')
@section('title', 'Transfer Requests')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Transfer Requests</h1>
                <p class="zems-header-subtitle">Review and manage teacher transfer requests</p>
            </div>
        </div>
    </div>

    <div class="zems-card-base p-5">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="status" class="zems-input h-[46px] min-w-[200px] cursor-pointer">
                <option value="">All Statuses</option>
                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button class="zems-btn-primary h-[46px] w-full sm:w-auto uppercase tracking-wide px-6">Filter</button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($transfers as $transfer)
        <div class="zems-card-base p-6 hover:border-blue-300 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                        <h3 class="font-bold text-slate-900 text-lg">{{ $transfer->teacher->nameWithInitials ?? 'Unknown Teacher' }}</h3>
                        <span class="
                            @if($transfer->status === 'PENDING') zems-badge-warning @endif
                            @if($transfer->status === 'APPROVED') zems-badge-success @endif
                            @if($transfer->status === 'REJECTED') zems-badge-primary !bg-red-50 !text-red-700 border-red-200 @endif
                        ">
                            {{ $transfer->status }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <span class="font-medium">{{ $transfer->fromSchool->name ?? '-' }}</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <span class="font-medium">{{ $transfer->toSchool->name ?? '-' }}</span>
                    </div>
                    @if($transfer->reason)
                    <p class="mt-2 text-sm text-slate-500">{{ $transfer->reason }}</p>
                    @endif
                    <p class="mt-1 text-xs text-slate-400">Requested: {{ $transfer->createdAt->format('M d, Y') }}</p>
                </div>

                @if($transfer->status === 'PENDING')
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('zonal.transfers.update', $transfer->id) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="APPROVED">
                        <button type="submit" class="zems-btn-primary !bg-emerald-600 hover:!bg-emerald-700">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('zonal.transfers.update', $transfer->id) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="REJECTED">
                        <button type="submit" class="zems-btn-primary !bg-red-600 hover:!bg-red-700">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="zems-card-base p-12 text-center text-slate-400 border-2 border-dashed border-slate-200">
            <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            <p class="text-lg font-bold text-slate-600">No transfer requests found.</p>
        </div>
        @endforelse
    </div>

    {{ $transfers->links() }}
</div>
@endsection
