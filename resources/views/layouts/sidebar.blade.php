{{-- Sidebar Navigation --}}
<aside :class="open ? 'translate-x-0' : '-translate-x-full'"
       class="fixed top-0 bottom-0 left-0 z-40 w-72 h-screen sidebar-gradient transform transition-transform duration-300 ease-out flex flex-col shadow-2xl md:translate-x-0 no-print">

    {{-- Logo --}}
    <div class="hidden md:block p-6 border-b border-white/10">
        <div class="flex items-center justify-center">
            <div class="w-full px-2">
                <img src="{{ asset('images/logo.png') }}" alt="ZEMS" class="w-full h-auto drop-shadow-xl">
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto animate-fade-in">
        @if(auth()->user()->isZonalAdmin())
            {{-- Section: Administration --}}
            <div class="px-4 py-2 mt-0 mb-2">
                <span class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Administration</span>
            </div>
            <div class="space-y-1">
                {{-- Overview --}}
                @include('layouts.partials.nav-item', ['route' => 'dashboard', 'label' => 'Overview', 'icon' => 'layout-dashboard'])

                {{-- Manage Schools (NavGroup) --}}
                @php
                    $schoolMenuActive = request()->routeIs('zonal.schools') || request()->routeIs('zonal.schools.*') || request()->routeIs('zonal.buildings') || request()->routeIs('zonal.buildings.*');
                @endphp
                <div x-data="{ expanded: {{ $schoolMenuActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="expanded = !expanded"
                        class="w-full group flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-left transition-all duration-200 ease-out
                            {{ $schoolMenuActive ? 'text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg transition-all duration-200
                            {{ $schoolMenuActive ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        <span class="flex-1">Manage Schools</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="expanded"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         style="display: none;"
                         class="space-y-1 ml-4 pl-3 border-l-2 border-white/10">
                        <a href="{{ route('zonal.schools') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('zonal.schools') && !request()->routeIs('zonal.schools.*')
                                      ? 'bg-white/15 text-white font-semibold'
                                      : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Overview
                        </a>
                        <a href="{{ route('zonal.schools.students') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('zonal.schools.students')
                                      ? 'bg-white/15 text-white font-semibold'
                                      : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Student Overview
                        </a>
                        <a href="{{ route('zonal.buildings') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('zonal.buildings*')
                                      ? 'bg-white/15 text-white font-semibold'
                                      : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Buildings & Facilities
                        </a>
                        <a href="{{ route('zonal.schools.remove') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('zonal.schools.remove') 
                                      ? 'bg-white/15 text-white font-semibold'
                                      : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Remove School
                        </a>
                    </div>
                </div>

                {{-- Manage Teachers (NavGroup) --}}
                @php
                    $teacherMenuActive = request()->routeIs('zonal.teachers') || request()->routeIs('zonal.teachers.*');
                @endphp
                <div x-data="{ expanded: {{ $teacherMenuActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="expanded = !expanded"
                        class="w-full group flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-left transition-all duration-200 ease-out
                            {{ $teacherMenuActive ? 'text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg transition-all duration-200
                            {{ $teacherMenuActive ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </span>
                        <span class="flex-1">Manage Teachers</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="expanded"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         style="display: none;"
                         class="space-y-1 ml-4 pl-3 border-l-2 border-white/10">
                        <a href="{{ route('zonal.teachers') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('zonal.teachers') && !request()->routeIs('zonal.teachers.*')
                                      ? 'bg-white/15 text-white font-semibold'
                                      : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Overview
                        </a>
                        <a href="{{ route('zonal.teachers.create') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('zonal.teachers.create')
                                      ? 'bg-white/15 text-white font-semibold'
                                      : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Add Teacher (Census)
                        </a>
                        <a href="{{ route('zonal.teachers.remove') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('zonal.teachers.remove')
                                      ? 'bg-white/15 text-white font-semibold'
                                      : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Remove Teacher
                        </a>
                    </div>
                </div>

                @include('layouts.partials.nav-item', ['route' => 'zonal.subjects', 'label' => 'Subject Management', 'icon' => 'book-open'])
                @include('layouts.partials.nav-item', ['route' => 'zonal.users', 'label' => 'User Management', 'icon' => 'user-plus'])
            </div>

            {{-- Section: Analytics & Audit --}}
            <div class="px-4 py-2 mt-6 mb-2">
                <span class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Analytics & Audit</span>
            </div>
            <div class="space-y-1">
                @include('layouts.partials.nav-item', ['route' => 'zonal.edit-requests', 'label' => 'Edit Requests', 'icon' => 'clipboard-edit'])
                @include('layouts.partials.nav-item', ['route' => 'zonal.salary-requests', 'label' => 'Salary Requests', 'icon' => 'receipt'])
                @include('layouts.partials.nav-item', ['route' => 'zonal.reports', 'label' => 'Reports', 'icon' => 'file-text'])
                @include('layouts.partials.nav-item', ['route' => 'zonal.audit', 'label' => 'Audit Logs', 'icon' => 'shield-alert'])
            </div>
        @endif

        @if(auth()->user()->isSchoolAdmin())
            <div class="px-4 py-2 mt-0 mb-2">
                <span class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">School Management</span>
            </div>
            <div class="space-y-1">
                @include('layouts.partials.nav-item', ['route' => 'dashboard', 'label' => 'Overview', 'icon' => 'layout-dashboard'])
                @include('layouts.partials.nav-item', ['route' => 'school.teachers', 'label' => 'Teachers', 'icon' => 'users'])
                @include('layouts.partials.nav-item', ['route' => 'school.students', 'label' => 'Manage Students', 'icon' => 'graduation-cap'])
                @include('layouts.partials.nav-item', ['route' => 'school.buildings', 'label' => 'Buildings & Facilities', 'icon' => 'building'])
            </div>
        @endif

        @if(auth()->user()->isTeacher())
            <div class="px-4 py-2 mt-0 mb-2">
                <span class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Personal</span>
            </div>
            <div class="space-y-1">
                @include('layouts.partials.nav-item', ['route' => 'dashboard', 'label' => 'My Profile', 'icon' => 'user'])
                @include('layouts.partials.nav-item', ['route' => 'teacher.salary', 'label' => 'Salary Requests', 'icon' => 'receipt'])
            </div>
        @endif
    </nav>

    {{-- User Section --}}
    <div class="p-4 border-t border-white/10 bg-black/20">
        <div class="flex items-center gap-3 mb-4 p-3 rounded-xl bg-white/5">
            <div class="w-11 h-11 rounded-xl @if(auth()->user()->isSuperAdmin()) bg-gradient-to-br from-purple-500 to-indigo-600 ring-2 ring-purple-400/50 @else bg-gradient-to-br from-blue-400 to-indigo-600 @endif flex items-center justify-center text-white font-bold text-lg shadow-lg">
                {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->email }}</p>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold
                    @if(auth()->user()->isSuperAdmin()) bg-purple-500/30 text-purple-200 border border-purple-400/40
                    @elseif(auth()->user()->isZonalAdmin()) bg-amber-500/20 text-amber-300
                    @elseif(auth()->user()->isSchoolAdmin()) bg-emerald-500/20 text-emerald-300
                    @else bg-sky-500/20 text-sky-300 @endif">
                    ✦ {{ auth()->user()->role_label }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-white/70 bg-white/5 rounded-xl hover:bg-red-500/20 hover:text-red-300 transition-all duration-200 group">
                <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>
