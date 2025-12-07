@extends('layouts.admin')

@section('content')
<div class="space-y-6">
                    <div class="max-w-3xl mx-auto">
                        <form action="#" method="POST">
                            @csrf
                            
                            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Customer Information</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                                        <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                                            <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone *</label>
                                            <input type="tel" name="phone" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                                        <textarea name="address" rows="3" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                                        <div class="flex flex-wrap gap-2 mb-2" id="selectedTags"></div>
                                        <select id="tagSelect" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                            <option value="">Select tags...</option>
                                            @foreach($available_tags as $tag)
                                            <option value="{{ $tag }}">{{ $tag }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                        <textarea name="notes" rows="4" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]"></textarea>
                                    </div>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <button type="submit" class="px-4 py-2 bg-[#5A8E74] hover:bg-[#4a7a64] text-white rounded-lg transition">
                                        Create Customer
                                    </button>
                                    <a href="{{ route('sales.customers.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        const selectedTags = new Set();
        const tagSelect = document.getElementById('tagSelect');
        const selectedTagsContainer = document.getElementById('selectedTags');

        tagSelect.addEventListener('change', function() {
            const tag = this.value;
            if (tag && !selectedTags.has(tag)) {
                selectedTags.add(tag);
                renderTags();
                this.value = '';
            }
        });

        function renderTags() {
            selectedTagsContainer.innerHTML = '';
            selectedTags.forEach(tag => {
                const tagEl = document.createElement('div');
                tagEl.className = 'inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm';
                tagEl.innerHTML = `
                    <span>${tag}</span>
                    <input type="hidden" name="tags[]" value="${tag}">
                    <button type="button" class="hover:text-blue-900 dark:hover:text-blue-100" onclick="removeTag('${tag}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                selectedTagsContainer.appendChild(tagEl);
            });
        }

        function removeTag(tag) {
            selectedTags.delete(tag);
            renderTags();
        }
</script>
@endsection
