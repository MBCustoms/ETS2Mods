@extends('layouts.admin')

@section('header', 'View Message')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.contacts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Messages</a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $message->subject }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">From {{ $message->name }} &lt;{{ $message->email }}&gt;</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $message->created_at->format('F j, Y g:i A') }}
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <div class="prose max-w-none text-gray-900">
                {!! nl2br(e($message->message)) !!}
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between items-center">
            <div class="text-xs text-gray-400">
                IP: {{ $message->ip_address }}
            </div>
            <form action="{{ route('admin.contacts.destroy', $message) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
