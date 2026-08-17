@extends('layouts.app')

@section('content')
<div x-data="productsPage()" x-init="init()">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Products</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Manage products across API versions</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-sm transition-colors">
            + Add Product
        </a>
    </div>

    <!-- Version Switcher -->
    <div class="mb-6">
        <div class="inline-flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
            <button @click="version = 'v1'; fetchProducts()"
                    :class="version === 'v1' ? 'bg-white dark:bg-gray-800 shadow text-blue-600' : 'text-gray-600 dark:text-gray-300'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all">
                V1
            </button>
            <button @click="version = 'v2'; fetchProducts()"
                    :class="version === 'v2' ? 'bg-white dark:bg-gray-800 shadow text-purple-600' : 'text-gray-600 dark:text-gray-300'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all">
                V2
            </button>
        </div>
        <span class="ml-3 text-xs text-gray-500 dark:text-gray-400"
              x-text="version === 'v1' ? 'Fields: name, price, sku, stock' : 'Fields: name, price, sku, stock, category, is_active'"></span>
    </div>

    <!-- V2 Search & Filter -->
    <div x-show="version === 'v2'" x-transition class="mb-4 flex gap-3">
        <div class="relative flex-1">
            <input type="text"
                   placeholder="Search by name or SKU..."
                   x-model="search"
                   @input.debounce.300ms="fetchProducts(1)"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <select x-model="activeFilter"
                @change="fetchProducts(1)"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="true">Active Only</option>
            <option value="false">Inactive Only</option>
        </select>
        <select x-model="categoryFilter"
                @change="fetchProducts(1)"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            <option value="">All Categories</option>
            <option value="Electronics">Electronics</option>
            <option value="Books">Books</option>
            <option value="Clothing">Clothing</option>
            <option value="Home">Home</option>
            <option value="Toys">Toys</option>
        </select>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div x-show="loading" class="p-8 text-center text-gray-500 dark:text-gray-400">
            Loading products...
        </div>

        <div x-show="!loading" x-cloak>
            <table class="w-full" x-show="products.length > 0">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">SKU</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                        <th x-show="version === 'v2'" class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                        <th x-show="version === 'v2'" class="text-center px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Active</th>
                        <th class="text-center px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="product in products" :key="product.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300" x-text="product.id"></td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white" x-text="product.name"></td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400" x-text="product.sku"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatPrice(product.price)"></td>
                            <td class="px-4 py-3 text-right" x-text="product.stock"></td>
                            <td x-show="version === 'v2'" class="px-4 py-3 text-gray-600 dark:text-gray-300" x-text="product.category || '—'"></td>
                            <td x-show="version === 'v2'" class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium"
                                      :class="product.is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-600/30 text-gray-600 dark:text-gray-300'"
                                      x-text="product.is_active ? 'Yes' : 'No'"></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center items-center space-x-2">
                                    <a :href="`/products/${version}/show/${product.id}`"
                                       class="p-1 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.457 24c.6-1.03 1.45-2.03 2.44-3a9 9 0 01.125-.032 8.983 8.988 0 013.076-.463A11.96 11.96 0 0012 19c1.5 0 2.87-.3 4-.83a12 12 0 003.66-2.5 11.96 11.96 0 00-3.66-2.42 11.8 11.8 0 00-3.66.25c-.21-.03-.42-.05-.63-.05a8.925 8.925 0 00-.62 3.07c-.01.05-.02.1-.02.15A8.965 8.965 0 0012 23c2.67 0 5.04-.87 7-2.4z" />
                                        </svg>
                                    </a>
                                    <a :href="`/products/${version}/edit/${product.id}`"
                                       class="p-1 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.445.045L9 9m0 0l-1.445 1.445M9 9V5h4" />
                                        </svg>
                                    </a>
                                    <button @click="deleteProduct(product.id)"
                                            class="p-1 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 5v5m0 0l-1.5-1.5m1.5 1.5l1.5-1.5M5 7V4a2 2 0 012-2h10a2 2 0 012 2v3M5 7h14" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div x-show="products.length === 0" class="p-8 text-center text-gray-500 dark:text-gray-400">
                No products found.
            </div>

            <!-- Pagination -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 flex items-center justify-between"
                 x-show="pagination.last_page > 1">
                <div class="flex-1 flex justify-between">
                    <button @click="fetchProducts(pagination.current_page - 1)"
                            :disabled="pagination.current_page <= 1"
                            class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-700">
                        Previous
                    </button>
                    <span class="px-3 py-1 text-sm text-gray-600 dark:text-gray-300"
                          x-text="`Page ${pagination.current_page} of ${pagination.last_page}`"></span>
                    <button @click="fetchProducts(pagination.current_page + 1)"
                            :disabled="pagination.current_page >= pagination.last_page"
                            class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-700">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function productsPage() {
    return {
        version: 'v2',
        search: '',
        activeFilter: '',
        categoryFilter: '',
        loading: true,
        products: [],
        pagination: { current_page: 1, last_page: 1, per_page: 15, total: 0 },

        async init() {
            await this.fetchProducts();
        },

        async fetchProducts(page = 1) {
            this.loading = true;
            let url = `/api/${this.version}/products?page=${page}&per_page=10`;

            if (this.version === 'v2') {
                if (this.search) url += `&search=${encodeURIComponent(this.search)}`;
                if (this.activeFilter) url += `&is_active=${this.activeFilter}`;
                if (this.categoryFilter) url += `&category=${encodeURIComponent(this.categoryFilter)}`;
            }

            try {
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.products = data.data;
                this.pagination = data.pagination;
            } catch (e) {
                this.products = [];
                this.pagination = { current_page: 1, last_page: 1, per_page: 10, total: 0 };
            }
            this.loading = false;
        },

        formatPrice(price) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price);
        },

        async deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;

            try {
                await fetch(`/api/${this.version}/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                await this.fetchProducts(this.pagination.current_page);
            } catch (e) {
                alert('Failed to delete product.');
            }
        }
    }
}
</script>
@endpush
