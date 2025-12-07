<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'document' => ['required', 'file', 'max:5120'],
        ]);

        $file = $data['document'];
        $path = $file->store('employee-documents', 'public');

        $employee->documents()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_path' => $path,
            'uploaded_at' => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function destroy(Employee $employee, EmployeeDocument $document)
    {
        if ($document->employee_id !== $employee->id) {
            abort(404);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
