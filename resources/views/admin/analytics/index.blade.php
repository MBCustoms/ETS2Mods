@extends('layouts.admin')

@section('header', 'Site Analytics')

@section('content')
<div class="space-y-6">
    <!-- Detailed Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Re-use similar cards or add new metrics here -->
         <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                     <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Visits (Est)</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_downloads'] * 3) }}</div> <!-- Mock calculation based on downloads -->
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                     <div class="flex-shrink-0">
                         <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Downloads</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_downloads']) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                     <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Registered Users</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                     <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['total_users'] > 0 ? number_format(($stats['total_downloads'] / $stats['total_users']), 1) : 0 }}
                                </div>
                                <span class="ml-1 text-xs text-gray-500">Downloads/User</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Traffic & Activity Trends</h3>
        <div class="h-80 w-full bg-gray-50 rounded border border-dashed border-gray-200 flex items-center justify-center">
            <!-- Reuse Dashboard Chart Logic or implement a more detailed one -->
             <div class="text-center w-full px-4">
                <div class="flex items-end justify-between space-x-2 h-64">
                        @foreach($chartData['labels'] as $index => $label)
                        <div class="flex flex-col items-center flex-1 group">
                            <div class="w-full flex justify-center space-x-1 items-end h-full">
                                <!-- Download Bar -->
                                @php $dlHeight = min(100, $chartData['downloads'][$index]); @endphp
                                <div class="w-4 bg-blue-500 rounded-t opacity-75" style="height: {{ $dlHeight == 0 ? 1 : $dlHeight }}%" title="{{ $chartData['downloads'][$index] }} Downloads"></div>
                                
                                <!-- Uploads Bar -->
                                @php $ulHeight = min(100, $chartData['uploads'][$index] * 5); @endphp
                                <div class="w-4 bg-orange-500 rounded-t opacity-75" style="height: {{ $ulHeight == 0 ? 1 : $ulHeight }}%" title="{{ $chartData['uploads'][$index] }} Uploads"></div>
                            </div>
                            <span class="mt-2 text-xs text-gray-500">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Top Downloads & Registrations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Top Downloads (Last 7 Days)</h3>
            <div class="flow-root">
                <ul role="list" class="-my-5 divide-y divide-gray-200">
                    @foreach($topDownloads as $download)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-8 w-8 rounded object-cover" src="{{ $download->mod->first_image_url ?? asset('images/no-image.png') }}" alt="">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $download->mod->title }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        By {{ $download->mod->user->name }}
                                    </p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $download->total }} dls
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">User Registrations (Last 30 Days)</h3>
             <div class="h-64 w-full bg-gray-50 rounded border border-dashed border-gray-200 flex items-center justify-center p-4">
                <div class="flex items-end justify-between space-x-1 h-full w-full">
                    @foreach($registrationStats['labels'] as $index => $label)
                        @php 
                            $count = $registrationStats['data'][$index];
                            $height = $count > 0 ? min(100, ($count / max(1, max($registrationStats['data']))) * 100) : 1;
                        @endphp
                        <div class="flex flex-col items-center flex-1 group">
                            <div class="w-full flex justify-center items-end h-full">
                                <div class="w-full bg-indigo-500 rounded-t opacity-75 mx-0.5" style="height: {{ $height }}%" title="{{ $count }} Users"></div>
                            </div>
                            @if($loop->iteration % 5 == 0)
                                <span class="mt-2 text-[10px] text-gray-500">{{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
