@extends('layouts.installer')

@section('title', 'Environment Setup')

@section('content')
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Environment Configuration</h3>

    <form action="{{ route('install.environment.save') }}" method="POST" id="env-form">
        @csrf
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <!-- App Settings -->
            <div class="sm:col-span-3">
                <label for="APP_NAME" class="block text-sm font-medium text-gray-700">App Name</label>
                <div class="mt-1">
                    <input type="text" name="APP_NAME" id="APP_NAME" value="{{ old('APP_NAME', env('APP_NAME', 'ETS2LT')) }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="APP_URL" class="block text-sm font-medium text-gray-700">App URL</label>
                <div class="mt-1">
                    <input type="url" name="APP_URL" id="APP_URL" value="{{ old('APP_URL', 'http://localhost:8000') }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                </div>
            </div>

            <div class="sm:col-span-6 border-t border-gray-200 my-4"></div>

            <!-- Database Settings -->
            <div class="sm:col-span-6">
                <h4 class="text-md font-medium text-gray-900 mb-2">Database Connection</h4>
            </div>

            <div class="sm:col-span-4">
                <label for="DB_HOST" class="block text-sm font-medium text-gray-700">Database Host</label>
                <div class="mt-1">
                    <input type="text" name="DB_HOST" id="DB_HOST" value="{{ old('DB_HOST', '127.0.0.1') }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="DB_PORT" class="block text-sm font-medium text-gray-700">Database Port</label>
                <div class="mt-1">
                    <input type="text" name="DB_PORT" id="DB_PORT" value="{{ old('DB_PORT', '3306') }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                </div>
            </div>

            <div class="sm:col-span-6">
                <label for="DB_DATABASE" class="block text-sm font-medium text-gray-700">Database Name</label>
                <div class="mt-1">
                    <input type="text" name="DB_DATABASE" id="DB_DATABASE" value="{{ old('DB_DATABASE', 'ets2lt') }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="DB_USERNAME" class="block text-sm font-medium text-gray-700">Database Username</label>
                <div class="mt-1">
                    <input type="text" name="DB_USERNAME" id="DB_USERNAME" value="{{ old('DB_USERNAME', 'root') }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="DB_PASSWORD" class="block text-sm font-medium text-gray-700">Database Password</label>
                <div class="mt-1">
                    <input type="password" name="DB_PASSWORD" id="DB_PASSWORD" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer')
    <div></div>
    <button type="submit" form="env-form" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
        Save & Continue
        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
@endsection
