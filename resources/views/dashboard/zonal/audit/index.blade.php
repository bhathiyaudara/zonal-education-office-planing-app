@extends('layouts.dashboard')
@section('title', 'Audit Logs')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="zems-header-title">Audit Logs</h1>
                <p class="zems-header-subtitle">Track system activity and changes</p>
            </div>
        </div>
    </div>

    <div class="zems-card-base p-5">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action (e.g. LOGIN, CREATE)..." class="zems-input pl-12 h-[46px]">
            </div>
            <button class="zems-btn-primary h-[46px] w-full sm:w-auto uppercase tracking-wide px-6">Filter</button>
        </form>
    </div>

    <div class="zems-table-container">
        <div class="overflow-x-auto">
            <table class="zems-table">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="zems-table-th">Time</th>
                        <th class="zems-table-th">User</th>
                        <th class="zems-table-th">Action</th>
                        <th class="zems-table-th">Resource</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="zems-table-row">
                        <td class="zems-table-td text-slate-500 text-xs">{{ $log->createdAt->format('M d, Y h:i A') }}</td>
                        <td class="zems-table-td font-medium">{{ $log->userEmail ?: ($log->user->email ?? '-') }}</td>
                        <td class="zems-table-td">
                            <span class="zems-badge-secondary">{{ $log->action }}</span>
                        </td>
                        <td class="zems-table-td text-slate-500 text-xs font-mono">{{ $log->resourceType }}:{{ Str::limit($log->resourceId, 12) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-lg font-bold">No audit logs found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $logs->links() }}
</div>
@endsection
