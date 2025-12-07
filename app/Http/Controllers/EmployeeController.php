<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query()->with('manager');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('employees.form', [
            'employee' => new Employee(),
            'managers' => Employee::orderBy('name')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $employee = Employee::create($data);

        return redirect()->route('employees.show', $employee)->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['manager', 'subordinates', 'documents']);

        return view('employees.show', [
            'employee' => $employee,
        ]);
    }

    public function edit(Employee $employee)
    {
        $employee->load('documents');

        return view('employees.form', [
            'employee' => $employee,
            'managers' => Employee::where('id', '!=', $employee->id)->orderBy('name')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $this->validatedData($request, $employee);

        if ($request->hasFile('profile_photo')) {
            if ($employee->profile_photo_path) {
                Storage::disk('public')->delete($employee->profile_photo_path);
            }
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $employee->update($data);

        return redirect()->route('employees.show', $employee)->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->profile_photo_path) {
            Storage::disk('public')->delete($employee->profile_photo_path);
        }

        foreach ($employee->documents as $document) {
            Storage::disk('public')->delete($document->file_path);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    private function validatedData(Request $request, ?Employee $employee = null): array
    {
        $employeeId = $employee?->id;

        return $request->validate([
            'employee_id' => ['required', 'string', 'max:191', Rule::unique('employees', 'employee_id')->ignore($employeeId)],
            'name' => ['required', 'string', 'max:191'],
            'gender' => ['nullable', 'string', 'max:20'],
            'dob' => ['nullable', 'date'],
            'national_id' => ['nullable', 'string', 'max:191'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:191'],
            'personal_email' => ['nullable', 'email', 'max:191'],
            'job_title' => ['nullable', 'string', 'max:191'],
            'department' => ['nullable', 'string', 'max:191'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'work_email' => ['nullable', 'email', 'max:191', Rule::unique('employees', 'work_email')->ignore($employeeId)],
            'work_phone' => ['nullable', 'string', 'max:191'],
            'employment_type' => ['nullable', 'string', 'max:191'],
            'hire_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:hire_date'],
            'status' => ['required', 'string', 'max:50'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
