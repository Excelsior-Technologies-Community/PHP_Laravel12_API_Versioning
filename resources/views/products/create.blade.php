@extends('layouts.app')

@section('content')
<div x-data="createProduct()" x-init="init()">
    <div class="max-w-3xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Product</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Create a new product (V2 API with all fields)</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                        <input type="text"
                               x-model="form.name"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <template x-if="errors.name">
                            <p class="mt-1 text-sm text-red-500" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea x-model="form.description"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Product description..."></textarea>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price *</label>
                        <input type="number"
                               step="0.01"
                               x-model.number="form.price"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <template x-if="errors.price">
                            <p class="mt-1 text-sm text-red-500" x-text="errors.price[0]"></p>
                        </template>
                    </div>

                    <!-- SKU -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU *</label>
                        <input type="text"
                               x-model="form.sku"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g. PRD-001">
                        <template x-if="errors.sku">
                            <p class="mt-1 text-sm text-red-500" x-text="errors.sku[0]"></p>
                        </template>
                    </div>

                    <!-- Stock -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock *</label>
                        <input type="number"
                               x-model.number="form.stock"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <template x-if="errors.stock">
                            <p class="mt-1 text-sm text-red-500" x-text="errors.stock[0]"></p>
                        </template>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                        <select x-model="form.category"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a category</option>
                            <option>Electronics</option>
                            <option>Books</option>
                            <option>Clothing</option>
                            <option>Home</option>
                            <option>Toys</option>
                        </select>
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-end">
                        <div class="flex items-center h-10">
                            <input type="checkbox"
                                   id="is_active"
                                   x-model.boolean="form.is_active"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active product</label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            :disabled="submitting"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-sm transition-colors disabled:opacity-50">
                        <template x-if="!submitting">Create Product</template>
                        <template x-if="submitting">Saving...</template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createProduct() {
    return {
        form: {
            name: '',
            description: '',
            price: '',
            sku: '',
            stock: '',
            category: '',
            is_active: true,
        },
        errors: {},
        submitting: false,
        csrf: null,

        async init() {
            const token = document.querySelector('meta[name="csrf-token"]');
            this.csrf = token ? token.content : '';
        },

        async submit() {
            this.submitting = true;
            this.errors = {};

            try {
                const res = await fetch('/api/v2/products', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(this.form),
                });

                if (res.ok) {
                    window.location.href = '/products';
                } else {
                    const data = await res.json();
                    if (data.errors) {
                        this.errors = data.errors;
                    } else {
                        alert(data.message || 'Failed to create product.');
                    }
                }
            } catch (e) {
                alert('An error occurred.');
            }
            this.submitting = false;
        }
    }
}
</script>
@endpush
