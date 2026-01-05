@extends('layouts.installer')

@section('title', 'Database Setup')

@section('content')
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Database Setup</h3>

    <div class="text-center py-8" x-data="databaseSetup()">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 mb-6" :class="{ 'bg-green-100': status === 'success', 'bg-red-100': status === 'error', 'bg-orange-100': status === 'running' }">
            <svg x-show="status === 'idle'" class="h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            <svg x-show="status === 'running'" class="animate-spin h-12 w-12 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg x-show="status === 'success'" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg x-show="status === 'error'" class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        <h2 class="text-xl font-medium text-gray-900 mb-2" x-text="message">Ready to install database</h2>
        <p class="text-gray-500 mb-6" x-show="status === 'idle'">Click the button below to migrate and seed the database.</p>

        <button x-show="status === 'idle' || status === 'error'" @click="runMigration" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
            Run Migrations
        </button>

        <a x-show="status === 'success'" href="{{ route('install.admin') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Continue to Admin Setup
        </a>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('databaseSetup', () => ({
                status: 'idle', // idle, running, success, error
                message: 'Ready to Run Migrations',
                
                async runMigration() {
                    this.status = 'running';
                    this.message = 'Migrating and Seeding database... This may take a while.';
                    
                    try {
                        const response = await fetch('{{ route('install.migrate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.status = 'success';
                            this.message = 'Database installed successfully!';
                        } else {
                            this.status = 'error';
                            this.message = 'Error: ' + data.message;
                        }
                    } catch (error) {
                        this.status = 'error';
                        this.message = 'A network error occurred.';
                    }
                }
            }))
        })
    </script>
@endsection
