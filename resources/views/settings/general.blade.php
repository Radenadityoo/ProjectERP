@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">General Settings</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Company profile and localization</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4" data-auto-hide="2500">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
        <form method="POST" action="{{ route('settings.general.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name', $general['company_name'] ?? '') }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Address</label>
                <textarea name="company_address" rows="3" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('company_address', $general['company_address'] ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Language</label>
                    <select name="default_language" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="id" @selected(old('default_language', $general['default_language'] ?? 'id')==='id')>Bahasa Indonesia</option>
                        <option value="en" @selected(old('default_language', $general['default_language'] ?? 'id')==='en')>English</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Timezone</label>
                    <input type="text" name="timezone" value="{{ old('timezone', $general['timezone'] ?? 'Asia/Jakarta') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Save Settings</button>
        </form>
    </div>
</div>
@endsection
