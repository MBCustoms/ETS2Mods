@extends('layouts.app')

@section('title', 'My Mods')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">My Mods</h1>
                    <p class="text-gray-500 mt-2">Manage all your uploaded mods</p>
                </div>

                @if($mods->count() > 0)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mod
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stats
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($mods as $mod)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-16 w-16">
                                                        <img class="h-16 w-16 rounded-md object-cover" src="{{ $mod->first_image_url ?: 'https://placehold.co/100x100?text=Mod' }}" alt="{{ $mod->title }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <a href="{{ route('mods.show', $mod) }}" class="hover:text-orange-600">{{ $mod->title }}</a>
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $mod->category->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($mod->status === 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @elseif($mod->status === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending Review
                                                    </span>
                                                @elseif($mod->status === 'rejected')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                    @if($mod->rejection_reason)
                                                        <div class="mt-1 text-xs text-gray-500">
                                                            Reason: {{ Str::limit($mod->rejection_reason, 50) }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="space-y-1">
                                                    <div>👁️ {{ number_format($mod->views_count) }}</div>
                                                    <div>⬇️ {{ number_format($mod->downloads_count) }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $mod->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('mods.show', $mod) }}" class="text-orange-600 hover:text-orange-900">View</a>
                                                @can('update', $mod)
                                                    <a href="{{ route('mods.edit', $mod) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3 border-t border-gray-200">
                            {{ $mods->links() }}
                        </div>
                    </div>
                @else
                    <div class="bg-white p-12 text-center rounded-lg shadow text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414a1 1 0 00-.707-.293H6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No mods uploaded yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start sharing your mods with the community!</p>
                        <div class="mt-6">
                            <a href="{{ route('mods.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
                                Submit Your First Mod
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/4 mt-8 lg:mt-0">
                <div class="sticky top-6">
                    <x-ad-slot slotName="my_mods_sidebar" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

