@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mode === 'create' ? 'Add Employee' : 'Edit Employee' }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $mode === 'create' ? 'Create a new employee record' : 'Update employee details' }}</p>
        </div>
        <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg">Back to list</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-4">
            <p class="font-semibold mb-2">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $mode === 'create' ? route('employees.store') : route('employees.update', $employee) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                            <select name="gender" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Select</option>
                                <option value="Male" @selected(old('gender', $employee->gender)==='Male')>Male</option>
                                <option value="Female" @selected(old('gender', $employee->gender)==='Female')>Female</option>
                                <option value="Other" @selected(old('gender', $employee->gender)==='Other')>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob', optional($employee->dob)->format('Y-m-d')) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">National ID</label>
                            <input type="text" name="national_id" value="{{ old('national_id', $employee->national_id) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                            <textarea name="address" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('address', $employee->address) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Personal Email</label>
                            <input type="email" name="personal_email" value="{{ old('personal_email', $employee->personal_email) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employment Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee ID *</label>
                            <input type="text" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Job Title</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $employee->job_title) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                            <input type="text" name="department" value="{{ old('department', $employee->department) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Manager</label>
                            <select name="manager_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">None</option>
                                @foreach($managers as $mgr)
                                    <option value="{{ $mgr->id }}" @selected(old('manager_id', $employee->manager_id)===$mgr->id)>{{ $mgr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Work Email</label>
                            <input type="email" name="work_email" value="{{ old('work_email', $employee->work_email) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Work Phone</label>
                            <input type="text" name="work_phone" value="{{ old('work_phone', $employee->work_phone) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employment Type</label>
                            <select name="employment_type" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Select</option>
                                @foreach(['Full-time','Contract','Intern','Part-time','Temporary'] as $type)
                                    <option value="{{ $type }}" @selected(old('employment_type', $employee->employment_type)===$type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hire Date</label>
                            <input type="date" name="hire_date" value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date', optional($employee->end_date)->format('Y-m-d')) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="Active" @selected(old('status', $employee->status ?? 'Active')==='Active')>Active</option>
                                <option value="Inactive" @selected(old('status', $employee->status)==='Inactive')>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employee Documents</h2>
                    @if($employee->exists)
                        <div class="space-y-4">
                            <form method="POST" action="{{ route('employees.documents.store', $employee) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-center gap-3">
                                @csrf
                                <input type="file" name="document" required class="w-full sm:w-auto">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Upload Document</button>
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
                                                <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $doc->file_type ?? '-' }}</td>
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
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">Save the employee first to upload documents.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profile Photo</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden flex items-center justify-center">
                            @if($employee->profile_photo_path)
                                {{-- @phpstan-ignore-next-line --}}
                                <img id="photo-preview" src="{{ asset('storage/' . $employee->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                            @else
                                <img id="photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode(old('name', $employee->name)) }}&background=0D8ABC&color=fff" alt="Profile Photo" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="block w-full text-sm text-gray-600 dark:text-gray-300">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG/JPG up to 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h3>
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">{{ $mode === 'create' ? 'Create Employee' : 'Save Changes' }}</button>
                        <a href="{{ route('employees.index') }}" class="px-4 py-2 text-center bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg font-semibold">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const photoInput = document.getElementById('profile_photo');
    const preview = document.getElementById('photo-preview');

    if (photoInput) {
        photoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => { preview.src = ev.target.result; };
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush
