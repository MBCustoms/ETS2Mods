@extends('layouts.admin')

@section('header', 'Control Center')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Mods Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-primary-orange">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Mods</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_mods']) }}</div>
                                <div class="ml-2 flex items-baseline text-xs font-semibold text-green-600">
                                    {{ $stats['active_mods'] }} Active
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Downloads Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Downloads</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_downloads']) }}</div>
                                <div class="ml-2 flex items-baseline text-xs font-semibold text-green-600">
                                    +{{ $stats['recent_downloads'] }} (24h)
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-indigo-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Users</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</div>
                                <div class="ml-2 flex items-baseline text-xs font-semibold text-indigo-600">
                                    {{ $stats['verified_users'] }} Verified
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                         <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Attention</dt>
                            <dd class="flex items-baseline">
                                <div class="text-lg font-bold text-gray-900">
                                    {{ $stats['pending_mods'] }} <span class="text-xs font-normal text-gray-500">Mods Pending</span>
                                </div>
                            </dd>
                            <dd class="mt-1 text-sm text-red-600 font-medium">
                                {{ $stats['pending_reports'] }} Open Reports
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Chart & Recent Activity -->
        <div class="lg:col-span-2 space-y-6">
             <!-- Activity Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Weekly Platform Activity</h3>
                <div class="relative h-64 border border-dashed border-gray-200 rounded flex items-center justify-center text-gray-500">
                    <div class="text-center w-full px-4">
                        <div class="flex items-end justify-between space-x-2 h-40">
                             @foreach($chartData['labels'] as $index => $label)
                                <div class="flex flex-col items-center flex-1 group">
                                    <div class="w-full flex justify-center space-x-1 items-end h-full">
                                        <!-- Download Bar -->
                                        @php $dlHeight = min(100, $chartData['downloads'][$index]); @endphp
                                        <div class="w-3 bg-blue-400 rounded-t transition-all duration-500 hover:bg-blue-600" style="height: {{ $dlHeight == 0 ? 1 : $dlHeight }}%"></div>
                                        
                                        <!-- Uploads Bar (Scaled x5 for visibility) -->
                                        @php $ulHeight = min(100, $chartData['uploads'][$index] * 10); @endphp
                                        <div class="w-3 bg-primary-orange-light rounded-t transition-all duration-500 hover:bg-primary-orange" style="height: {{ $ulHeight == 0 ? 1 : $ulHeight }}%"></div>
                                    </div>
                                    <span class="mt-2 text-xs text-gray-500">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-center space-x-6 text-xs text-gray-500">
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-blue-400 mr-1"></span> Downloads</span>
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-primary-orange mr-1"></span> New Mods</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Users Table -->
             <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Newest Users</h3>
                </div>
                <ul role="list" class="divide-y divide-gray-200">
                    @forelse($recentUsers as $user)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                         <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                            <span class="text-xs font-medium leading-none text-white">{{ substr($user->name, 0, 1) }}</span>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-primary-orange">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                                    @if($user->is_verified)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Verified</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-4 text-sm text-gray-500 text-center">No new users.</li>
                    @endforelse
                </ul>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('admin.users.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View all users <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Priority Alerts & Top Modders -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Critical Reports -->
            @if($criticalReports->count() > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 shadow-sm rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Critical Reports</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc pl-5 space-y-1">
                                    @foreach($criticalReports as $report)
                                        <li>
                                            <a href="#" class="font-bold underline hover:text-red-900">{{ $report->reportable->title ?? 'Content' }}</a>
                                            <span class="block text-xs opacity-75">Reason: {{ $report->reason }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('admin.reports.index') }}" class="text-sm font-medium text-red-800 hover:text-red-900">Resolve now <span aria-hidden="true">&rarr;</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Top Modders -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Top Modders</h3>
                </div>
                <ul role="list" class="divide-y divide-gray-200">
                     @forelse($topModders as $modder)
                        <li class="px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-orange-light text-white text-xs font-bold">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $modder->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $modder->mods_count }} Uploads</p>
                                </div>
                                <div>
                                    <!-- Badge placeholder -->
                                </div>
                            </div>
                        </li>
                    @empty
                         <li class="px-4 py-4 text-sm text-gray-500 text-center">No Data</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
