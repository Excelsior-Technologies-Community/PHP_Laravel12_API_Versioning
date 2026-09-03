@extends('layouts.app')

@section('content')

<div
    x-data="productsPage()"
    x-init="init()"
    class="space-y-6">

    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Product Management
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Manage products across API versions
            </p>

        </div>


        <div class="flex items-center gap-3">

            <button
                @click="fetchStatistics()"
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700
                       hover:bg-gray-200 dark:hover:bg-gray-600
                       text-gray-700 dark:text-gray-200
                       rounded-lg text-sm font-medium">
                Refresh Statistics
            </button>


            <a
                href="{{ route('products.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700
                       text-white rounded-lg font-medium shadow-sm">
                + Add Product
            </a>

        </div>

    </div>


    {{-- ================================================================
         VERSION SWITCHER
    ================================================================= --}}

    <div class="flex flex-wrap items-center gap-3">

        <div class="inline-flex items-center
                    bg-gray-100 dark:bg-gray-700
                    rounded-lg p-1">

            <button
                @click="changeVersion('v1')"
                :class="version === 'v1'
                    ? 'bg-white dark:bg-gray-800 shadow text-blue-600'
                    : 'text-gray-600 dark:text-gray-300'"
                class="px-4 py-2 rounded-md text-sm font-medium">
                V1
            </button>


            <button
                @click="changeVersion('v2')"
                :class="version === 'v2'
                    ? 'bg-white dark:bg-gray-800 shadow text-purple-600'
                    : 'text-gray-600 dark:text-gray-300'"
                class="px-4 py-2 rounded-md text-sm font-medium">
                V2
            </button>

        </div>


        <span
            class="text-xs text-gray-500 dark:text-gray-400"
            x-text="
                version === 'v1'
                ? 'Legacy / Deprecated API'
                : 'Current API with advanced product management'
            "></span>

    </div>


    {{-- ================================================================
         V1 WARNING
    ================================================================= --}}

    <div
        x-show="version === 'v1'"
        x-cloak
        class="p-4 bg-yellow-50 dark:bg-yellow-900/20
               border border-yellow-200 dark:border-yellow-800
               rounded-lg">

        <div class="flex items-start gap-3">

            <div class="text-yellow-500 text-xl">
                ⚠
            </div>

            <div>

                <p class="font-semibold text-yellow-800 dark:text-yellow-200">
                    V1 API is deprecated
                </p>

                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                    V1 will sunset on January 1, 2027.
                    Please migrate clients to V2.
                </p>

                <a
                    href="{{ url('/api/versions') }}"
                    target="_blank"
                    class="inline-block mt-2 text-sm
                           font-medium underline
                           text-yellow-800 dark:text-yellow-200">
                    View API version information →
                </a>

            </div>

        </div>

    </div>


    {{-- ================================================================
         V2 STATISTICS
    ================================================================= --}}

    <div
        x-show="version === 'v2'"
        x-cloak
        class="grid grid-cols-1 sm:grid-cols-2
               lg:grid-cols-4 gap-4">

        {{-- Total --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total Products
            </p>

            <p
                class="text-2xl font-bold text-gray-900 dark:text-white mt-2"
                x-text="statistics.total_products">
                0
            </p>

        </div>


        {{-- Active --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Active Products
            </p>

            <p
                class="text-2xl font-bold text-green-600 mt-2"
                x-text="statistics.active_products">
                0
            </p>

        </div>


        {{-- Inactive --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Inactive Products
            </p>

            <p
                class="text-2xl font-bold text-gray-500 mt-2"
                x-text="statistics.inactive_products">
                0
            </p>

        </div>


        {{-- Featured --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Featured Products
            </p>

            <p
                class="text-2xl font-bold text-yellow-500 mt-2"
                x-text="statistics.featured_products">
                0
            </p>

        </div>


        {{-- Average Price --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Average Price
            </p>

            <p
                class="text-xl font-bold text-gray-900 dark:text-white mt-2"
                x-text="formatPrice(statistics.average_price)">
                $0.00
            </p>

        </div>


        {{-- Highest Price --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Highest Price
            </p>

            <p
                class="text-xl font-bold text-gray-900 dark:text-white mt-2"
                x-text="formatPrice(statistics.highest_price)">
                $0.00
            </p>

        </div>


        {{-- Lowest Price --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Lowest Price
            </p>

            <p
                class="text-xl font-bold text-gray-900 dark:text-white mt-2"
                x-text="formatPrice(statistics.lowest_price)">
                $0.00
            </p>

        </div>


        {{-- Total Stock --}}

        <div class="bg-white dark:bg-gray-800
                    rounded-xl shadow-sm
                    border border-gray-200 dark:border-gray-700
                    p-5">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total Stock
            </p>

            <p
                class="text-2xl font-bold text-blue-600 mt-2"
                x-text="statistics.total_stock">
                0
            </p>

        </div>

    </div>


    {{-- ================================================================
         V2 ADVANCED FILTERS
    ================================================================= --}}

    <div
        x-show="version === 'v2'"
        x-cloak
        class="bg-white dark:bg-gray-800
               rounded-xl shadow-sm
               border border-gray-200 dark:border-gray-700
               p-5">

        <div class="flex flex-col lg:flex-row
                    lg:items-center lg:justify-between
                    gap-4 mb-5">

            <div>

                <h2 class="text-lg font-semibold
                           text-gray-900 dark:text-white">
                    Search & Advanced Filters
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Search, filter and sort products.
                </p>

            </div>


            <button
                @click="resetFilters()"
                class="px-3 py-2 text-sm
                       bg-gray-100 dark:bg-gray-700
                       hover:bg-gray-200 dark:hover:bg-gray-600
                       rounded-lg text-gray-700 dark:text-gray-200">
                Reset Filters
            </button>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2
                    lg:grid-cols-4 gap-4">


            {{-- Search --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Search
                </label>

                <input
                    type="text"
                    x-model="search"
                    @input.debounce.300ms="fetchProducts(1)"
                    placeholder="Name, SKU or category..."
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-blue-500">

            </div>


            {{-- Status --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Status
                </label>

                <select
                    x-model="activeFilter"
                    @change="fetchProducts(1)"
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white">

                    <option value="">
                        All Status
                    </option>

                    <option value="true">
                        Active
                    </option>

                    <option value="false">
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Category --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Category
                </label>

                <select
                    x-model="categoryFilter"
                    @change="fetchProducts(1)"
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white">

                    <option value="">
                        All Categories
                    </option>

                    <option value="Electronics">
                        Electronics
                    </option>

                    <option value="Books">
                        Books
                    </option>

                    <option value="Clothing">
                        Clothing
                    </option>

                    <option value="Home">
                        Home
                    </option>

                    <option value="Toys">
                        Toys
                    </option>

                </select>

            </div>


            {{-- Featured --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Featured
                </label>

                <select
                    x-model="featuredFilter"
                    @change="fetchProducts(1)"
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white">

                    <option value="">
                        All Products
                    </option>

                    <option value="true">
                        Featured
                    </option>

                    <option value="false">
                        Not Featured
                    </option>

                </select>

            </div>


            {{-- Min Price --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Min Price
                </label>

                <input
                    type="number"
                    min="0"
                    x-model="minPrice"
                    @change="fetchProducts(1)"
                    placeholder="0"
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white">

            </div>


            {{-- Max Price --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Max Price
                </label>

                <input
                    type="number"
                    min="0"
                    x-model="maxPrice"
                    @change="fetchProducts(1)"
                    placeholder="100000"
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white">

            </div>


            {{-- Stock Status --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Stock
                </label>

                <select
                    x-model="stockStatus"
                    @change="fetchProducts(1)"
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white">

                    <option value="">
                        All Stock
                    </option>

                    <option value="in_stock">
                        In Stock
                    </option>

                    <option value="low_stock">
                        Low Stock
                    </option>

                    <option value="out_of_stock">
                        Out of Stock
                    </option>

                </select>

            </div>


            {{-- Sort --}}

            <div>

                <label class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300 mb-1">
                    Sort By
                </label>

                <select
                    x-model="sort"
                    @change="fetchProducts(1)"
                    class="w-full px-4 py-2
                           border border-gray-300 dark:border-gray-600
                           rounded-lg bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white">

                    {{-- IMPORTANT: ID SORTING --}}

                    <option value="id-asc">
                        ID 1-9
                    </option>

                    <option value="id-desc">
                        ID 9-1
                    </option>

                    <option value="created_at-desc">
                        Newest
                    </option>

                    <option value="created_at-asc">
                        Oldest
                    </option>

                    <option value="name-asc">
                        Name A-Z
                    </option>

                    <option value="name-desc">
                        Name Z-A
                    </option>

                    <option value="price-asc">
                        Price Low-High
                    </option>

                    <option value="price-desc">
                        Price High-Low
                    </option>

                    <option value="stock-asc">
                        Stock Low-High
                    </option>

                    <option value="stock-desc">
                        Stock High-Low
                    </option>

                </select>

            </div>

        </div>

    </div>


    {{-- ================================================================
         PRODUCTS TABLE
    ================================================================= --}}

    <div
        class="bg-white dark:bg-gray-800
               rounded-xl shadow-sm
               border border-gray-200 dark:border-gray-700
               overflow-hidden">

        {{-- Loading --}}

        <div
            x-show="loading"
            class="p-10 text-center
                   text-gray-500 dark:text-gray-400">

            <div
                class="animate-spin rounded-full h-8 w-8
                       border-b-2 border-blue-600 mx-auto"></div>

            <p class="mt-3">
                Loading products...
            </p>

        </div>


        <div
            x-show="!loading"
            x-cloak>

            {{-- Result Header --}}

            <div
                class="px-5 py-4
                       border-b border-gray-200 dark:border-gray-700
                       flex flex-col sm:flex-row
                       sm:items-center sm:justify-between gap-3">

                <div>

                    <span
                        class="text-sm text-gray-500 dark:text-gray-400">
                        Products:
                    </span>

                    <span
                        class="font-semibold text-gray-900 dark:text-white"
                        x-text="pagination.total">
                        0
                    </span>

                </div>


                <div
                    class="text-xs text-gray-500 dark:text-gray-400"
                    x-show="version === 'v2'">

                    <span x-text="currentQueryDescription">
                        No filters
                    </span>

                </div>

            </div>


            {{-- Table --}}

            <div class="overflow-x-auto">

                <table
                    class="w-full"
                    x-show="products.length > 0">

                    <thead
                        class="bg-gray-50 dark:bg-gray-700/50
                               border-b border-gray-200
                               dark:border-gray-700">

                        <tr>

                            <th
                                class="text-left px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                ID
                            </th>


                            <th
                                class="text-left px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                Name
                            </th>


                            <th
                                class="text-left px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                SKU
                            </th>


                            <th
                                class="text-right px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                Price
                            </th>


                            <th
                                class="text-right px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                Stock
                            </th>


                            <th
                                x-show="version === 'v2'"
                                class="text-left px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                Category
                            </th>


                            <th
                                x-show="version === 'v2'"
                                class="text-center px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                Active
                            </th>


                            <th
                                x-show="version === 'v2'"
                                class="text-center px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                Featured
                            </th>


                            <th
                                class="text-center px-4 py-3
                                       text-xs font-medium
                                       text-gray-500 dark:text-gray-300
                                       uppercase">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y divide-gray-200
                               dark:divide-gray-700">

                        <template
                            x-for="product in products"
                            :key="product.id">

                            <tr
                                class="hover:bg-gray-50
                                       dark:hover:bg-gray-700/30">

                                {{-- ID --}}

                                <td
                                    class="px-4 py-4
                                           text-gray-600
                                           dark:text-gray-300"
                                    x-text="product.id"></td>


                                {{-- Name --}}

                                <td
                                    class="px-4 py-4">

                                    <div
                                        class="font-medium
                                               text-gray-900
                                               dark:text-white"
                                        x-text="product.name"></div>

                                    <div
                                        x-show="product.description"
                                        class="text-xs
                                               text-gray-500
                                               dark:text-gray-400
                                               mt-1 max-w-xs truncate"
                                        x-text="product.description"></div>

                                </td>


                                {{-- SKU --}}

                                <td
                                    class="px-4 py-4
                                           text-gray-600
                                           dark:text-gray-400"
                                    x-text="product.sku"></td>


                                {{-- Price --}}

                                <td
                                    class="px-4 py-4 text-right
                                           font-medium
                                           text-gray-900
                                           dark:text-white"
                                    x-text="formatPrice(product.price)"></td>


                                {{-- Stock --}}

                                <td
                                    class="px-4 py-4 text-right">

                                    <span
                                        class="px-2 py-1
                                               rounded-full text-xs
                                               font-medium"
                                        :class="
                                            Number(product.stock) === 0
                                            ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                            : Number(product.stock) <= 10
                                            ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300'
                                            : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                        "
                                        x-text="
                                            Number(product.stock) === 0
                                            ? 'Out of Stock'
                                            : Number(product.stock) <= 10
                                            ? product.stock + ' Low'
                                            : product.stock
                                        "></span>

                                </td>


                                {{-- Category --}}

                                <td
                                    x-show="version === 'v2'"
                                    class="px-4 py-4
                                           text-gray-600
                                           dark:text-gray-300"
                                    x-text="product.category || '—'"></td>


                                {{-- Active --}}

                                <td
                                    x-show="version === 'v2'"
                                    class="px-4 py-4 text-center">

                                    <button
                                        @click="toggleStatus(product.id)"
                                        class="px-2 py-1
                                               rounded-full text-xs
                                               font-medium"
                                        :class="
                                            product.is_active
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'
                                        "
                                        x-text="
                                            product.is_active
                                            ? 'Active'
                                            : 'Inactive'
                                        "></button>

                                </td>


                                {{-- Featured --}}

                                <td
                                    x-show="version === 'v2'"
                                    class="px-4 py-4 text-center">

                                    <button
                                        @click="toggleFeatured(product.id)"
                                        class="text-xl hover:scale-110 transition"
                                        :title="
                                            product.is_featured
                                            ? 'Remove Featured'
                                            : 'Mark Featured'
                                        "
                                        x-text="
                                            product.is_featured
                                            ? '⭐'
                                            : '☆'
                                        "></button>

                                </td>


                                {{-- Actions --}}

                                <td
                                    class="px-4 py-4">

                                    <div
                                        class="flex justify-center
                                               items-center gap-2">

                                        <a
                                            :href="`/products/${version}/show/${product.id}`"
                                            class="p-2
                                                   text-blue-600
                                                   dark:text-blue-400
                                                   hover:bg-blue-50
                                                   dark:hover:bg-blue-900/30
                                                   rounded-lg"
                                            title="View">

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                            </svg>

                                        </a>


                                        <a
                                            :href="`/products/${version}/edit/${product.id}`"
                                            class="p-2
                                                   text-yellow-600
                                                   dark:text-yellow-400
                                                   hover:bg-yellow-50
                                                   dark:hover:bg-yellow-900/30
                                                   rounded-lg"
                                            title="Edit">

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />

                                            </svg>

                                        </a>


                                        <button
                                            @click="deleteProduct(product.id)"
                                            class="p-2
                                                   text-red-600
                                                   dark:text-red-400
                                                   hover:bg-red-50
                                                   dark:hover:bg-red-900/30
                                                   rounded-lg"
                                            title="Move to Trash">

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 7h14M10 11v6M14 11v6M9 7V4h6v3" />

                                            </svg>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        </template>

                    </tbody>

                </table>

            </div>


            {{-- No Products --}}

            <div
                x-show="products.length === 0"
                class="p-10 text-center">

                <div class="text-4xl mb-3">
                    📦
                </div>

                <p
                    class="font-medium
                           text-gray-900
                           dark:text-white">
                    No products found
                </p>

                <p
                    class="text-sm
                           text-gray-500
                           dark:text-gray-400 mt-1">
                    Try changing your search or filters.
                </p>

            </div>


            {{-- ========================================================
                 PAGINATION
            ========================================================= --}}

            <div
                x-show="pagination.last_page > 1"
                class="border-t
                       border-gray-200
                       dark:border-gray-700
                       px-5 py-4">

                <div
                    class="flex flex-col sm:flex-row
                           sm:items-center
                           sm:justify-between gap-3">

                    <p
                        class="text-sm
                               text-gray-500
                               dark:text-gray-400">

                        Page

                        <span
                            class="font-semibold
                                   text-gray-900
                                   dark:text-white"
                            x-text="pagination.current_page"></span>

                        of

                        <span
                            class="font-semibold
                                   text-gray-900
                                   dark:text-white"
                            x-text="pagination.last_page"></span>

                        —

                        <span
                            x-text="pagination.total"></span>

                        products

                    </p>


                    <div class="flex items-center gap-2">

                        {{-- Previous --}}

                        <button
                            @click="fetchProducts(
                                pagination.current_page - 1
                            )"
                            :disabled="
                                pagination.current_page <= 1
                            "
                            class="px-3 py-2 border
                                   border-gray-300
                                   dark:border-gray-600
                                   rounded-lg text-sm
                                   disabled:opacity-50
                                   disabled:cursor-not-allowed">
                            Previous
                        </button>


                        {{-- Numeric Pages --}}

                        <template
                            x-for="page in pageNumbers()"
                            :key="page">

                            <button
                                @click="fetchProducts(page)"
                                class="px-3 py-2 border
                                       rounded-lg text-sm"
                                :class="
                                    pagination.current_page === page
                                    ? 'bg-blue-600 text-white border-blue-600'
                                    : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300'
                                "
                                x-text="page">
                            </button>

                        </template>


                        {{-- Next --}}

                        <button
                            @click="fetchProducts(
                                pagination.current_page + 1
                            )"
                            :disabled="
                                pagination.current_page
                                >= pagination.last_page
                            "
                            class="px-3 py-2 border
                                   border-gray-300
                                   dark:border-gray-600
                                   rounded-lg text-sm
                                   disabled:opacity-50
                                   disabled:cursor-not-allowed">
                            Next
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         TRASH SECTION
    ================================================================= --}}

    <div
        x-show="version === 'v2'"
        x-cloak
        class="bg-white dark:bg-gray-800
               rounded-xl shadow-sm
               border border-gray-200 dark:border-gray-700">

        <div
            class="p-5 border-b
                   border-gray-200
                   dark:border-gray-700
                   flex flex-col sm:flex-row
                   sm:items-center
                   sm:justify-between gap-3">

            <div>

                <h2
                    class="text-lg font-semibold
                           text-gray-900
                           dark:text-white">
                    🗑️ Trash
                </h2>

                <p
                    class="text-sm
                           text-gray-500
                           dark:text-gray-400">
                    Deleted products can be restored.
                </p>

            </div>


            <button
                @click="fetchTrash()"
                class="px-3 py-2 text-sm
                       bg-gray-100
                       dark:bg-gray-700
                       rounded-lg
                       hover:bg-gray-200
                       dark:hover:bg-gray-600">
                Refresh Trash
            </button>

        </div>


        <div
            x-show="trashLoading"
            class="p-6 text-center
                   text-gray-500 dark:text-gray-400">
            Loading trash...
        </div>


        <div
            x-show="!trashLoading && trash.length === 0"
            class="p-8 text-center
                   text-gray-500 dark:text-gray-400">
            Trash is empty.
        </div>


        <div
            x-show="!trashLoading && trash.length > 0"
            x-cloak
            class="overflow-x-auto">

            <table class="w-full min-w-[700px]">

                <thead
                    class="bg-gray-50 dark:bg-gray-700/50">

                    <tr>

                        <th
                            class="text-left px-4 py-3
                                   text-xs uppercase
                                   text-gray-500">
                            ID
                        </th>

                        <th
                            class="text-left px-4 py-3
                                   text-xs uppercase
                                   text-gray-500">
                            Product
                        </th>

                        <th
                            class="text-left px-4 py-3
                                   text-xs uppercase
                                   text-gray-500">
                            SKU
                        </th>

                        <th
                            class="text-left px-4 py-3
                                   text-xs uppercase
                                   text-gray-500">
                            Deleted
                        </th>

                        <th
                            class="text-center px-4 py-3
                                   text-xs uppercase
                                   text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y
                           divide-gray-200
                           dark:divide-gray-700">

                    <template
                        x-for="product in trash"
                        :key="product.id">

                        <tr>

                            <td
                                class="px-4 py-4"
                                x-text="product.id"></td>


                            <td
                                class="px-4 py-4
                                       font-medium
                                       text-gray-900
                                       dark:text-white"
                                x-text="product.name"></td>


                            <td
                                class="px-4 py-4
                                       text-gray-500"
                                x-text="product.sku"></td>


                            <td
                                class="px-4 py-4
                                       text-gray-500"
                                x-text="
                                    product.deleted_at
                                    ? formatDateTime(product.deleted_at)
                                    : '—'
                                "></td>


                            <td
                                class="px-4 py-4">

                                <div
                                    class="flex justify-center gap-2">

                                    <button
                                        @click="restoreProduct(product.id)"
                                        class="px-3 py-1.5
                                               text-sm
                                               bg-green-100
                                               text-green-700
                                               dark:bg-green-900/30
                                               dark:text-green-300
                                               rounded-lg">
                                        Restore
                                    </button>


                                    <button
                                        @click="
                                            permanentlyDeleteProduct(
                                                product.id
                                            )
                                        "
                                        class="px-3 py-1.5
                                               text-sm
                                               bg-red-100
                                               text-red-700
                                               dark:bg-red-900/30
                                               dark:text-red-300
                                               rounded-lg">
                                        Delete Forever
                                    </button>

                                </div>

                            </td>

                        </tr>

                    </template>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>
    function productsPage() {

        return {

            /*
            |--------------------------------------------------------------------------
            | Version
            |--------------------------------------------------------------------------
            */

            version: 'v2',


            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            search: '',

            activeFilter: '',

            categoryFilter: '',

            featuredFilter: '',

            minPrice: '',

            maxPrice: '',

            stockStatus: '',


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            | Default ID ASC
            |--------------------------------------------------------------------------
            */

            sort: 'id-asc',


            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            products: [],

            loading: true,


            /*
            |--------------------------------------------------------------------------
            | Pagination
            |--------------------------------------------------------------------------
            */

            pagination: {

                current_page: 1,

                last_page: 1,

                per_page: 5,

                total: 0

            },


            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            statistics: {

                total_products: 0,

                active_products: 0,

                inactive_products: 0,

                featured_products: 0,

                in_stock_products: 0,

                out_of_stock_products: 0,

                average_price: 0,

                highest_price: 0,

                lowest_price: 0,

                total_stock: 0

            },


            /*
            |--------------------------------------------------------------------------
            | Trash
            |--------------------------------------------------------------------------
            */

            trash: [],

            trashLoading: false,


            /*
            |--------------------------------------------------------------------------
            | Initialize
            |--------------------------------------------------------------------------
            */

            async init() {

                await Promise.all([

                    this.fetchProducts(1),

                    this.fetchStatistics(),

                    this.fetchTrash()

                ]);

            },


            /*
            |--------------------------------------------------------------------------
            | Change Version
            |--------------------------------------------------------------------------
            */

            async changeVersion(version) {

                this.version = version;

                this.resetFilters(false);

                await this.fetchProducts(1);

            },


            /*
            |--------------------------------------------------------------------------
            | Fetch Products
            |--------------------------------------------------------------------------
            */

            async fetchProducts(page = 1) {

                /*
                |--------------------------------------------------------------------------
                | Prevent invalid pages
                |--------------------------------------------------------------------------
                */

                if (page < 1) {

                    page = 1;

                }


                this.loading = true;


                try {

                    const params =
                        new URLSearchParams();


                    /*
                    |--------------------------------------------------------------------------
                    | EXACTLY 5 PRODUCTS PER PAGE
                    |--------------------------------------------------------------------------
                    */

                    params.set(
                        'page',
                        page
                    );

                    params.set(
                        'per_page',
                        5
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | V2 Filters
                    |--------------------------------------------------------------------------
                    */

                    if (this.version === 'v2') {


                        /*
                        | Search
                        */

                        if (
                            this.search &&
                            this.search.trim() !== ''
                        ) {

                            params.set(
                                'search',
                                this.search.trim()
                            );

                        }


                        /*
                        | Active
                        */

                        if (
                            this.activeFilter !== ''
                        ) {

                            params.set(
                                'is_active',
                                this.activeFilter
                            );

                        }


                        /*
                        | Category
                        */

                        if (
                            this.categoryFilter !== ''
                        ) {

                            params.set(
                                'category',
                                this.categoryFilter
                            );

                        }


                        /*
                        | Featured
                        */

                        if (
                            this.featuredFilter !== ''
                        ) {

                            params.set(
                                'is_featured',
                                this.featuredFilter
                            );

                        }


                        /*
                        | Minimum Price
                        */

                        if (
                            this.minPrice !== ''
                        ) {

                            params.set(
                                'min_price',
                                this.minPrice
                            );

                        }


                        /*
                        | Maximum Price
                        */

                        if (
                            this.maxPrice !== ''
                        ) {

                            params.set(
                                'max_price',
                                this.maxPrice
                            );

                        }


                        /*
                        | Stock Status
                        */

                        if (
                            this.stockStatus !== ''
                        ) {

                            params.set(
                                'stock_status',
                                this.stockStatus
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SORTING
                        |--------------------------------------------------------------------------
                        |
                        | id-asc  => sort=id&sort_order=asc
                        | id-desc => sort=id&sort_order=desc
                        |
                        */

                        const parts =
                            this.sort.split('-');


                        const sortField =
                            parts[0];

                        const sortOrder =
                            parts[1];


                        params.set(
                            'sort',
                            sortField
                        );


                        params.set(
                            'sort_order',
                            sortOrder
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | API URL
                    |--------------------------------------------------------------------------
                    */

                    const url =
                        `/api/${this.version}/products?${params.toString()}`;


                    console.log(
                        'Product API:',
                        url
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Request
                    |--------------------------------------------------------------------------
                    */

                    const response =
                        await fetch(
                            url, {

                                method: 'GET',

                                headers: {

                                    'Accept': 'application/json',

                                    'X-Requested-With': 'XMLHttpRequest'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | JSON
                    |--------------------------------------------------------------------------
                    */

                    const data =
                        await response.json();


                    /*
                    |--------------------------------------------------------------------------
                    | Products
                    |--------------------------------------------------------------------------
                    */

                    this.products =
                        Array.isArray(data.data) ?
                        data.data :
                        [];


                    /*
                    |--------------------------------------------------------------------------
                    | Pagination
                    |--------------------------------------------------------------------------
                    */

                    if (data.pagination) {

                        this.pagination = {

                            current_page: Number(
                                data.pagination.current_page
                            ),

                            last_page: Number(
                                data.pagination.last_page
                            ),

                            /*
                            | Always 5
                            */

                            per_page: 5,

                            total: Number(
                                data.pagination.total
                            )

                        };

                    } else {

                        this.pagination = {

                            current_page: 1,

                            last_page: 1,

                            per_page: 5,

                            total: 0

                        };

                    }


                } catch (error) {

                    console.error(
                        'Product loading error:',
                        error
                    );


                    this.products = [];


                    this.pagination = {

                        current_page: 1,

                        last_page: 1,

                        per_page: 5,

                        total: 0

                    };

                } finally {

                    this.loading = false;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Fetch Statistics
            |--------------------------------------------------------------------------
            */

            async fetchStatistics() {

                try {

                    const response =
                        await fetch(
                            '/api/v2/products/statistics', {

                                headers: {

                                    'Accept': 'application/json'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    const data =
                        await response.json();


                    this.statistics =
                        data.statistics ||
                        this.statistics;


                } catch (error) {

                    console.error(
                        'Statistics error:',
                        error
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Toggle Status
            |--------------------------------------------------------------------------
            */

            async toggleStatus(id) {

                try {

                    const response =
                        await fetch(
                            `/api/v2/products/${id}/toggle-status`, {

                                method: 'PATCH',

                                headers: {

                                    'Accept': 'application/json',

                                    'X-Requested-With': 'XMLHttpRequest'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    await Promise.all([

                        this.fetchProducts(
                            this.pagination.current_page
                        ),

                        this.fetchStatistics()

                    ]);


                } catch (error) {

                    console.error(
                        'Status update error:',
                        error
                    );


                    alert(
                        'Failed to update product status.'
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Toggle Featured
            |--------------------------------------------------------------------------
            */

            async toggleFeatured(id) {

                try {

                    const response =
                        await fetch(
                            `/api/v2/products/${id}/toggle-featured`, {

                                method: 'PATCH',

                                headers: {

                                    'Accept': 'application/json',

                                    'X-Requested-With': 'XMLHttpRequest'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    await Promise.all([

                        this.fetchProducts(
                            this.pagination.current_page
                        ),

                        this.fetchStatistics()

                    ]);


                } catch (error) {

                    console.error(
                        'Featured update error:',
                        error
                    );


                    alert(
                        'Failed to update featured status.'
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            async deleteProduct(id) {

                if (
                    !confirm(
                        'Move this product to trash?'
                    )
                ) {

                    return;

                }


                try {

                    const response =
                        await fetch(
                            `/api/v2/products/${id}`, {

                                method: 'DELETE',

                                headers: {

                                    'Accept': 'application/json',

                                    'X-Requested-With': 'XMLHttpRequest'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | If last item on page is deleted,
                    | move back to previous page
                    |--------------------------------------------------------------------------
                    */

                    let page =
                        this.pagination.current_page;


                    if (
                        this.products.length === 1 &&
                        page > 1
                    ) {

                        page--;

                    }


                    await Promise.all([

                        this.fetchProducts(page),

                        this.fetchStatistics(),

                        this.fetchTrash()

                    ]);


                } catch (error) {

                    console.error(
                        'Delete error:',
                        error
                    );


                    alert(
                        'Failed to move product to trash.'
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Fetch Trash
            |--------------------------------------------------------------------------
            */

            async fetchTrash() {

                this.trashLoading = true;


                try {

                    const response =
                        await fetch(
                            '/api/v2/products/trash', {

                                headers: {

                                    'Accept': 'application/json'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    const data =
                        await response.json();


                    this.trash =
                        Array.isArray(data.data) ?
                        data.data :
                        [];


                } catch (error) {

                    console.error(
                        'Trash error:',
                        error
                    );


                    this.trash = [];

                } finally {

                    this.trashLoading = false;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Restore
            |--------------------------------------------------------------------------
            */

            async restoreProduct(id) {

                if (
                    !confirm(
                        'Restore this product?'
                    )
                ) {

                    return;

                }


                try {

                    const response =
                        await fetch(
                            `/api/v2/products/${id}/restore`, {

                                method: 'POST',

                                headers: {

                                    'Accept': 'application/json',

                                    'X-Requested-With': 'XMLHttpRequest'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    await Promise.all([

                        this.fetchProducts(1),

                        this.fetchStatistics(),

                        this.fetchTrash()

                    ]);


                } catch (error) {

                    console.error(
                        'Restore error:',
                        error
                    );


                    alert(
                        'Failed to restore product.'
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Permanent Delete
            |--------------------------------------------------------------------------
            */

            async permanentlyDeleteProduct(id) {

                if (
                    !confirm(
                        'WARNING: This will permanently delete the product. Continue?'
                    )
                ) {

                    return;

                }


                try {

                    const response =
                        await fetch(
                            `/api/v2/products/${id}/force-delete`, {

                                method: 'DELETE',

                                headers: {

                                    'Accept': 'application/json',

                                    'X-Requested-With': 'XMLHttpRequest'

                                }

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    await Promise.all([

                        this.fetchStatistics(),

                        this.fetchTrash()

                    ]);


                } catch (error) {

                    console.error(
                        'Permanent delete error:',
                        error
                    );


                    alert(
                        'Failed to permanently delete product.'
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Reset Filters
            |--------------------------------------------------------------------------
            */

            resetFilters(
                reload = true
            ) {

                this.search = '';

                this.activeFilter = '';

                this.categoryFilter = '';

                this.featuredFilter = '';

                this.minPrice = '';

                this.maxPrice = '';

                this.stockStatus = '';


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                | Reset to ID ASC
                |--------------------------------------------------------------------------
                */

                this.sort = 'id-asc';


                if (reload) {

                    this.fetchProducts(1);

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Numeric Pagination
            |--------------------------------------------------------------------------
            */

            pageNumbers() {

                const pages = [];


                for (
                    let i = 1; i <= this.pagination.last_page; i++
                ) {

                    pages.push(i);

                }


                return pages;

            },


            /*
            |--------------------------------------------------------------------------
            | Filter Description
            |--------------------------------------------------------------------------
            */

            get currentQueryDescription() {

                const filters = [];


                if (this.search) {

                    filters.push(
                        `Search: ${this.search}`
                    );

                }


                if (
                    this.activeFilter !== ''
                ) {

                    filters.push(

                        this.activeFilter === 'true' ?
                        'Active' :
                        'Inactive'

                    );

                }


                if (this.categoryFilter) {

                    filters.push(
                        this.categoryFilter
                    );

                }


                if (
                    this.featuredFilter !== ''
                ) {

                    filters.push(

                        this.featuredFilter === 'true' ?
                        'Featured' :
                        'Not Featured'

                    );

                }


                if (this.minPrice) {

                    filters.push(
                        `Min: ${this.minPrice}`
                    );

                }


                if (this.maxPrice) {

                    filters.push(
                        `Max: ${this.maxPrice}`
                    );

                }


                if (this.stockStatus) {

                    filters.push(
                        this.stockStatus.replace(
                            '_',
                            ' '
                        )
                    );

                }


                if (filters.length === 0) {

                    return 'No filters';

                }


                return filters.join(' • ');

            },


            /*
            |--------------------------------------------------------------------------
            | Format Price
            |--------------------------------------------------------------------------
            */

            formatPrice(price) {

                return new Intl.NumberFormat(
                    'en-US', {

                        style: 'currency',

                        currency: 'USD'

                    }
                ).format(
                    Number(price || 0)
                );

            },


            /*
            |--------------------------------------------------------------------------
            | Format Date
            |--------------------------------------------------------------------------
            */

            formatDateTime(date) {

                if (!date) {

                    return '—';

                }


                return new Date(date)
                    .toLocaleString(
                        'en-US', {

                            year: 'numeric',

                            month: 'short',

                            day: 'numeric',

                            hour: '2-digit',

                            minute: '2-digit'

                        }
                    );

            }

        };

    }
</script>

@endpush
