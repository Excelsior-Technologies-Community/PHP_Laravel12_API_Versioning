@extends('layouts.app')

@section('content')
<div x-data="dashboard()" x-init="init()" class="space-y-6">
    <!-- Health Check Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">System Health</h2>
            <span class="px-3 py-1 rounded-full text-xs font-semibold"
                  :class="healthStatus === 'ok'
                    ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                    : (healthStatus === 'error'
                        ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300'
                        : 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300')">
                <span x-text="healthStatus === 'ok' ? '✓ Healthy' : (healthStatus === 'error' ? '✗ Issues Detected' : 'Checking...')"></span>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center space-x-4 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                 :class="checks.database ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20'">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                         :class="checks.database ? 'bg-green-100 dark:bg-green-800/40' : 'bg-red-100 dark:bg-red-800/40'">
                        <svg class="w-5 h-5"
                             :class="checks.database ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 7v10c0 2.21.77 4.25 2 5.66l1-1.73C8.54 19.64 9.55 20 10.5 20h3c.95 0 1.96-.36 2.67-1.07l1 1.73A8.014 8.014 0 0020 17V7a4 4 0 00-4-4h-1a4 4 0 00-3 1.33A4 4 0 009 4H8a4 4 0 00-4 4z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Database</p>
                    <p class="text-sm font-semibold"
                       :class="checks.database ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        <span x-text="checks.database ? 'Connected' : 'Disconnected'"></span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="driver"></p>
                </div>
            </div>

            <div class="flex items-center space-x-4 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                 :class="checks.cache ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20'">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                         :class="checks.cache ? 'bg-green-100 dark:bg-green-800/40' : 'bg-red-100 dark:bg-red-800/40'">
                        <svg class="w-5 h-5"
                             :class="checks.cache ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 9a3 3 0 106 0 3 3 0 01-6 0zM8 9l3 3 3-3" />
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cache</p>
                    <p class="text-sm font-semibold"
                       :class="checks.cache ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        <span x-text="checks.cache ? 'Working' : 'Failed'"></span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">File cache driver</p>
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-lg bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 p-3">
            <p class="text-xs font-mono text-gray-500 dark:text-gray-400">
                API Response:
                <span class="text-gray-700 dark:text-gray-300" x-text="healthStatus === 'loading' ? 'Checking...' : ('Status: ' + healthStatus + ' | database=' + checks.database + ' cache=' + checks.cache + ' driver=' + driver)"></span>
            </p>
        </div>
    </div>

    <!-- API Endpoints Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">API Endpoints</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-300">Method</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-300">Endpoint</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-300">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded">GET</span></td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">/api/health</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">Health check (DB + Cache)</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded">GET</span></td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">/api/v1/products</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">List V1 products (name, price, sku, stock)</td>                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded">GET</span></td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">/api/v2/products</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">List V2 products (+ category, is_active, search/filter)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Version Comparison Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Version Comparison</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="text-left px-4 py-2 font-medium text-gray-500 dark:text-gray-300">Field</th>
                        <th class="text-center px-4 py-2 font-medium text-blue-600 dark:text-blue-400">V1</th>
                        <th class="text-center px-4 py-2 font-medium text-purple-600 dark:text-purple-400">V2</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">name</td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">description</td>
                        <td class="text-center px-4 py-2"><span class="text-red-500">✗</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">price</td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">sku</td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">stock</td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">category</td>
                        <td class="text-center px-4 py-2"><span class="text-red-500">✗</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">is_active</td>
                        <td class="text-center px-4 py-2"><span class="text-red-500">✗</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">created_at / updated_at</td>
                        <td class="text-center px-4 py-2"><span class="text-red-500">✗</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">search &amp; filter</td>
                        <td class="text-center px-4 py-2"><span class="text-red-500">✗</span></td>
                        <td class="text-center px-4 py-2"><span class="text-green-500">✓</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 text-center">
            <div class="text-3xl font-bold text-gray-900 dark:text-white" x-text="productCount"></div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Products</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 text-center">
            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">v2</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Latest Version</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 text-center">
            <a href="{{ route('products.index') }}"
               class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium text-sm">
                Manage Products →
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dashboard() {
    return {
        healthStatus: 'loading',
        driver: '-',
        checks: { database: false, cache: false },
        productCount: '-',
        async init() {
            await this.fetchHealth();
            await this.fetchProductCount();
        },
        async fetchHealth() {
            try {
                const res = await fetch('/api/health');
                const data = await res.json();
                this.healthStatus = data.status;
                this.driver = data.driver || '-';
                this.checks = data.checks;
            } catch (e) {
                this.healthStatus = 'error';
            }
        },
        async fetchProductCount() {
            try {
                const res = await fetch('/api/v2/products?per_page=1');
                const data = await res.json();
                this.productCount = data.pagination?.total ?? '-';
            } catch (e) {
                this.productCount = '-';
            }
        }
    }
}
</script>
@endpush
