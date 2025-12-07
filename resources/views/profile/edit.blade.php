@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Profile</h2>

            @if(session('status') === 'profile-updated')
                <div class="mt-4 text-sm text-green-600 dark:text-green-400">Profile updated.</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[13px] font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input name="name" value="{{ old('name', $user->name) }}" class="mt-2 w-full border rounded px-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" style="height:44px;" />
                        @error('name') <div class="text-sm text-red-600 dark:text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input name="email" value="{{ old('email', $user->email) }}" class="mt-2 w-full border rounded px-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" style="height:44px;" />
                        @error('email') <div class="text-sm text-red-600 dark:text-red-400">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-erp text-white rounded">Save</button>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">Cancel</a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Change Password</h3>
            <form method="POST" action="{{ route('password.update') }}" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[13px] font-medium text-gray-700 dark:text-gray-300">Current password</label>
                    <input name="current_password" type="password" class="mt-2 w-full border rounded px-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" style="height:44px;" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[13px] font-medium text-gray-700 dark:text-gray-300">New password</label>
                        <input name="password" type="password" class="mt-2 w-full border rounded px-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" style="height:44px;" />
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-gray-700 dark:text-gray-300">Confirm password</label>
                        <input name="password_confirmation" type="password" class="mt-2 w-full border rounded px-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" style="height:44px;" />
                    </div>
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-erp text-white rounded">Change password</button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Danger Zone</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Permanently delete your account.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
                @csrf
                @method('DELETE')
                <label class="block text-[13px] font-medium text-gray-700 dark:text-gray-300">Confirm with password</label>
                <input name="password" type="password" class="mt-2 w-full border rounded px-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" style="height:44px;" />
                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete account</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
