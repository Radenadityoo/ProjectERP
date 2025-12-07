@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden flex items-center justify-center">
                    @if($employee->profile_photo_path)
                        {{-- @phpstan-ignore-next-line --}}
                        <img src="{{ asset('storage/' . $employee->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=0D8ABC&color=fff" alt="Profile Photo" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $employee->name }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $employee->job_title ?? '—' }} {{ $employee->department ? '• '.$employee->department : '' }}</p>
                </div>
            </div>
            <div class="mt-2">
                @if($employee->status === 'Active')
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                @else
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200">Inactive</span>
                @endif
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg font-semibold">Back</a>
            <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Edit</a>
            <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Delete this employee?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Personal Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Employee ID</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->employee_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Gender</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->gender ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Date of Birth</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ optional($employee->dob)->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">National ID</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->national_id ?? '—' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-gray-500 dark:text-gray-400">Address</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->address ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Phone</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Personal Email</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->personal_email ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employment Details</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Job Title</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->job_title ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Department</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->department ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Manager</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->manager?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Work Email</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->work_email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Work Phone</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->work_phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Employment Type</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->employment_type ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Hire Date</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ optional($employee->hire_date)->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">End Date</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ optional($employee->end_date)->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Documents</h2>
                <div class="space-y-4">
                    <form method="POST" action="{{ route('employees.documents.store', $employee) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-center gap-3">
                        @csrf
                        <input type="file" name="document" required class="w-full sm:w-auto">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Upload</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Uploaded</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($employee->documents as $doc)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $doc->file_name }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $doc->file_type ?? '—' }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $doc->uploaded_at?->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center gap-3">
                                                {{-- @phpstan-ignore-next-line --}}
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                                <form method="POST" action="{{ route('employees.documents.destroy', [$employee, $doc]) }}" onsubmit="return confirm('Delete this document?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-600 dark:text-gray-300">No documents uploaded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Summary</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Employee ID</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->employee_id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Hire Date</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ optional($employee->hire_date)->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Employment Type</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->employment_type ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Manager</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $employee->manager?->name ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
