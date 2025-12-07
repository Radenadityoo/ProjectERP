@extends('layouts.admin')

@section('content')
<form action="{{ route('purchase.vendors.update', $vendor->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="p-6 max-w-4xl space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Vendor Name *</label>
                    <input type="text" name="name" required class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ $vendor->name }}">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Email</label>
                    <input type="email" name="email" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ $vendor->email }}">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Phone</label>
                    <input type="text" name="phone" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ $vendor->phone }}">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Payment Terms</label>
                    <select name="payment_terms" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="15 days" @if($vendor->payment_terms == '15 days') selected @endif>15 days</option>
                        <option value="30 days" @if($vendor->payment_terms == '30 days') selected @endif>30 days</option>
                        <option value="45 days" @if($vendor->payment_terms == '45 days') selected @endif>45 days</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Address</label>
                <textarea name="address" rows="2" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">{{ $vendor->address }}</textarea>
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Notes</label>
                <textarea name="notes" rows="3" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">{{ $vendor->notes }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('purchase.vendors.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium text-center">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded-xl bg-erp text-white hover:bg-opacity-90 text-sm font-semibold">Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection
    </div>

    <div class="flex gap-3">
        <a href="{{ route('purchase.vendors.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium">Cancel</a>
        <button class="px-4 py-2 rounded-xl bg-erp text-white hover:bg-opacity-90 text-sm font-semibold" onclick="saveVendor()">Save</button>
    </div>
</div>

@push('scripts')
<script>
    function saveVendor() {
        alert('Vendor updated (demo)');
    }
</script>
@endpush
@endsection
