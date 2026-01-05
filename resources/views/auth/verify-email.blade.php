@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Verify Your Email Address
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Before proceeding, please check your email for a verification link.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            @if (session('message'))
                <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <div class="mb-4 text-sm text-gray-600">
                If you did not receive the email, click the button below to request another verification email.
            </div>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Resend Verification Email
                </button>
            </form>

            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-orange-600 hover:text-orange-500">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

