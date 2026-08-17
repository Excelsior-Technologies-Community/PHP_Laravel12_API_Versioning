@extends('layouts.app')

@section('content')
<div x-data="showProduct()" x-init="init()">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Product Details
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        (Showing via <span x-text="version === 'v1' ? 'V1' : 'V2'" class="text-blue-600 dark:text-blue-400"></span>)
                    </span>
                </h1>
            </div>
            <div class="flex space-x-2">
                <a :href="`/products/${version}/edit/${productId}`"
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium shadow-sm transition-colors">
                    Edit Product
                </a>
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium shadow-sm transition-colors">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Loading -->
        <div x-show="loading" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            Loading product...
        </div>

        <!-- Product Detail -->
        <div x-show="!loading" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
                 x-show="product">

                <!-- Product Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="product.name"></h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="product.sku"></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium"
                              :class="product.is_active
                                  ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                  : 'bg-gray-100 dark:bg-gray-600/30 text-gray-600 dark:text-gray-300'">
                            <template x-if="product.is_active">Active</template>
                            <template x-if="!product.is_active">Inactive</template>
                        </span>
                    </div>
                </div>

                <!-- Product Body -->
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Description</h3>
                            <p class="text-gray-900 dark:text-white" x-text="product.description || 'No description provided.'"></p>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</h3>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white" x-text="formatPrice(product.price)"></p>
                            </div>
                            <div>
                                <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Stock</h3>
                                <p class="text-lg font-semibold"
                                   :class="product.stock > 10 ? 'text-green-600 dark:text-green-400' : product.stock > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400'"
                                   x-text="product.stock"></p>
                            </div>
                            <div x-show="version === 'v2'">
                                <h3 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</h3>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white" x-text="product.category || 'Uncategorized'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Version Info -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">ID:</span>
                                <span class="text-gray-900 dark:text-white" x-text="product.id"></span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Last Updated:</span>
                                <span class="text-gray-900 dark:text-white" x-text="formatDate(product.updated_at)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version Warning -->
            <div x-show="version === 'v1'" x-cloak
                 class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.95 2.207l.95-.95a3 3 0 11 .705 1.228l-.95.95a3 3 0 01-1.705-1.228zm10.44-7.95a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <span class="font-medium">Note:</span> You are viewing this product via V1 API. Category and active status fields are not available in V1.
                        <a href="/products/v2/show/{{ $id }}" class="underline text-yellow-600 dark:text-yellow-300">Switch to V2</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showProduct() {
    return {
        version: null,
        productId: null,
        product: null,
        loading: true,

        async init() {
            const urlParts = window.location.pathname.split('/');
            this.version = urlParts[2];
            this.productId = urlParts[4];
            await this.fetchProduct();
        },

        async fetchProduct() {
            this.loading = true;
            try {
                const res = await fetch(`/api/${this.version}/products/${this.productId}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) {
                    if (res.status === 404) {
                        alert('Product not found.');
                        window.history.back();
                        return;
                    }
                    throw new Error('Failed to load product.');
                }

                const data = await res.json();
                this.product = data.data;
            } catch (e) {
                alert('Failed to load product.');
            }
            this.loading = false;
        },

        formatPrice(price) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price);
        },

        formatDate(dateStr) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateStr).toLocaleDateString('en-US', options);
        }
    }
}
</script>
@endpush
