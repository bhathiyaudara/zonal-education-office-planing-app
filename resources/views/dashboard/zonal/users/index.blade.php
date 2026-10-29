@extends('layouts.dashboard')
@section('title', 'User Management')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="confirmDelete">

    {{-- Header Banner --}}
    <div class="zems-header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h1 class="zems-header-title">User Management</h1>
                <p class="zems-header-subtitle">Manage administrative accounts for the zone and schools</p>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-900 border border-emerald-200 p-4 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="font-medium">Success</h3>
                <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 text-red-900 border border-red-200 p-4 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3 class="font-medium">Error</h3>
                <ul class="text-sm text-red-700 mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Controls --}}
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <form method="GET" class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." 
                   class="zems-input pl-10 h-[44px]"
                   onchange="this.form.submit()">
            {{-- Keeping role select hidden but functional --}}
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
        </form>

        <button onclick="document.getElementById('addUserModal').classList.remove('hidden')"
                class="zems-btn-primary w-full sm:w-auto h-[44px]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Create User
        </button>
    </div>

    {{-- Table --}}
    <div class="zems-table-container">
        <div class="overflow-x-auto">
            <table class="zems-table">
                <thead>
                    <tr class="zems-table-header-row">
                        <th class="zems-table-th">User</th>
                        <th class="zems-table-th">Role</th>
                        <th class="zems-table-th">School / Zone</th>
                        <th class="zems-table-th">Status</th>
                        <th class="zems-table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="zems-table-row">
                        <td class="zems-table-td">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $user->name ?: '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="zems-table-td">
                            @if($user->isSuperAdmin())
                            <span class="zems-badge-primary !bg-purple-100 !text-purple-800 !border-purple-300 font-bold">
                                Super Administrator
                            </span>
                            @elseif($user->role === 'ZONAL_ADMIN')
                            <span class="zems-badge-primary">
                                {{ $user->role_label }}
                            </span>
                            @elseif($user->role === 'SCHOOL_ADMIN')
                            <span class="zems-badge-primary !bg-emerald-50 !text-emerald-700 !border-emerald-200">
                                {{ $user->role_label }}
                            </span>
                            @else
                            <span class="zems-badge-primary !bg-slate-100 !text-slate-700 !border-slate-200">
                                {{ $user->role_label }}
                            </span>
                            @endif
                        </td>
                        <td class="zems-table-td font-semibold text-slate-600">
                            {{ $user->school->name ?? 'Zonal Education Office' }}
                        </td>
                        <td class="zems-table-td">
                            @if($user->isActive)
                            <span class="zems-badge-success">
                                Active
                            </span>
                            @else
                            <span class="zems-badge-warning">
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="zems-table-td text-right">
                            <button type="button" 
                                    data-user="{{ json_encode([
                                        'id' => $user->id,
                                        'name' => $user->name,
                                        'email' => $user->email,
                                        'role' => $user->role,
                                        'schoolId' => $user->schoolId,
                                        'isActive' => $user->isActive
                                    ]) }}"
                                    onclick="openEditModal(JSON.parse(this.dataset.user))" 
                                    class="zems-btn-secondary !px-3 !py-1.5 inline-flex ml-auto border-none !bg-transparent text-blue-600 hover:bg-blue-50">
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>No users found.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- Add User Modal --}}
    <div id="addUserModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-scale-in">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Create User Account</h2>
                <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form method="POST" action="{{ route('zonal.users.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="zems-input-label">Full Name *</label>
                    <input type="text" name="name" required class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Email *</label>
                    <input type="email" name="email" required class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Password *</label>
                    <input type="password" name="password" required minlength="6" class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Role *</label>
                    <select name="role" required class="zems-input">
                        @if(auth()->user()->isSuperAdmin())
                        <option value="SUPER_ADMIN">Super Admin (Full System Control)</option>
                        @endif
                        <option value="ZONAL_ADMIN">Zonal Admin</option>
                        <option value="SCHOOL_ADMIN">School Admin</option>
                        <option value="TEACHER">Teacher</option>
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">School</label>
                    <select name="schoolId" class="zems-input">
                        <option value="">No School</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="zems-btn-primary w-full shadow-md">
                    Create User
                </button>
            </form>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div id="editUserModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-scale-in">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Edit User Account</h2>
                <button onclick="document.getElementById('editUserModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form id="editUserForm" method="POST" action="" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="zems-input-label">Full Name *</label>
                    <input type="text" id="edit_name" name="name" required class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Email *</label>
                    <input type="email" id="edit_email" name="email" required class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Password <span class="text-slate-400 font-normal lowercase">(Leave blank to keep current)</span></label>
                    <input type="password" id="edit_password" name="password" minlength="6" class="zems-input">
                </div>
                <div>
                    <label class="zems-input-label">Role *</label>
                    <select id="edit_role" name="role" required class="zems-input">
                        @if(auth()->user()->isSuperAdmin())
                        <option value="SUPER_ADMIN">Super Admin (Full System Control)</option>
                        @endif
                        <option value="ZONAL_ADMIN">Zonal Admin</option>
                        <option value="SCHOOL_ADMIN">School Admin</option>
                        <option value="TEACHER">Teacher</option>
                    </select>
                </div>
                <div>
                    <label class="zems-input-label">School</label>
                    <select id="edit_schoolId" name="schoolId" class="zems-input">
                        <option value="">No School</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-2 pb-2">
                    <input type="checkbox" id="edit_isActive" name="isActive" value="1" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <label for="edit_isActive" class="text-sm font-semibold text-slate-700">Account Active</label>
                </div>
                <button type="submit" class="zems-btn-primary w-full shadow-md">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(user) {
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_schoolId').value = user.schoolId || '';
        document.getElementById('edit_isActive').checked = user.isActive == 1 || user.isActive == true;
        
        let url = '{{ route("zonal.users.update", ":id") }}';
        url = url.replace(':id', user.id);
        document.getElementById('editUserForm').action = url;
        
        document.getElementById('editUserModal').classList.remove('hidden');
    }
</script>
@endsection
