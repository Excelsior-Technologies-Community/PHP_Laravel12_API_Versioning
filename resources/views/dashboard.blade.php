@extends('layouts.app')

@section('content')

<div
    x-data="dashboard()"
    x-init="init()"
    class="space-y-6"
>

    <!-- API Version Status -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    API Version Management
                </h1>

                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Monitor API versions, deprecation status and migration timelines.
                </p>
            </div>

            <div class="px-4 py-2 rounded-lg bg-green-100 dark:bg-green-900/30">
                <span class="text-sm font-semibold text-green-700 dark:text-green-300">
                    Current Version:
                    <span x-text="currentVersion"></span>
                </span>
            </div>

        </div>

        <!-- Version Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- V1 -->
            <div class="rounded-xl border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20 p-5">

                <div class="flex items-center justify-between mb-4">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            V1
                        </h2>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Legacy API
                        </p>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300">
                        Deprecated
                    </span>

                </div>

                <div class="space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">
                            Released
                        </span>

                        <span class="font-medium text-gray-900 dark:text-white">
                            Aug 1, 2026
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">
                            Sunset
                        </span>

                        <span class="font-medium text-red-600 dark:text-red-400">
                            Jan 1, 2027
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">
                            Migration
                        </span>

                        <span class="font-medium text-blue-600 dark:text-blue-400">
                            V2
                        </span>
                    </div>

                </div>

                <div class="mt-5 p-3 rounded-lg bg-white/70 dark:bg-gray-800/50">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        ⚠ V1 is deprecated. Existing clients should migrate to V2 before the sunset date.
                    </p>
                </div>

            </div>

            <!-- V2 -->
            <div class="rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-5">

                <div class="flex items-center justify-between mb-4">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            V2
                        </h2>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Current API
                        </p>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300">
                        Current
                    </span>

                </div>

                <div class="space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">
                            Released
                        </span>

                        <span class="font-medium text-gray-900 dark:text-white">
                            Sep 1, 2026
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">
                            Status
                        </span>

                        <span class="font-medium text-green-600 dark:text-green-400">
                            Recommended
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">
                            Migration
                        </span>

                        <span class="font-medium text-gray-900 dark:text-white">
                            —
                        </span>
                    </div>

                </div>

                <div class="mt-5 p-3 rounded-lg bg-white/70 dark:bg-gray-800/50">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        ✓ V2 is the latest and recommended API version.
                    </p>
                </div>

            </div>

        </div>

    </div>


    <!-- Version Lifecycle -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            API Version Lifecycle
        </h2>

        <div class="relative">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Released -->
                <div class="text-center">

                    <div class="mx-auto w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">
                            1
                        </span>
                    </div>

                    <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">
                        Released
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        API version becomes available.
                    </p>

                </div>

                <!-- Deprecated -->
                <div class="text-center">

                    <div class="mx-auto w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                        <span class="text-yellow-600 dark:text-yellow-400 font-bold">
                            2
                        </span>
                    </div>

                    <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">
                        Deprecated
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Clients are encouraged to migrate.
                    </p>

                </div>

                <!-- Sunset -->
                <div class="text-center">

                    <div class="mx-auto w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <span class="text-red-600 dark:text-red-400 font-bold">
                            3
                        </span>
                    </div>

                    <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">
                        Sunset
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Version is scheduled for retirement.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- Version Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

        <div class="flex items-center justify-between mb-4">

            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    API Version Information
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Data loaded from the version management API.
                </p>
            </div>

            <button
                @click="fetchVersions()"
                class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600"
            >
                Refresh
            </button>

        </div>

        <div
            x-show="loadingVersions"
            class="py-8 text-center text-gray-500 dark:text-gray-400"
        >
            Loading version information...
        </div>

        <div
            x-show="!loadingVersions"
            x-cloak
            class="overflow-x-auto"
        >

            <table class="w-full text-sm">

                <thead class="bg-gray-50 dark:bg-gray-700/50">

                    <tr>

                        <th class="text-left px-4 py-3 text-gray-500 dark:text-gray-300">
                            Version
                        </th>

                        <th class="text-left px-4 py-3 text-gray-500 dark:text-gray-300">
                            Status
                        </th>

                        <th class="text-left px-4 py-3 text-gray-500 dark:text-gray-300">
                            Released
                        </th>

                        <th class="text-left px-4 py-3 text-gray-500 dark:text-gray-300">
                            Sunset
                        </th>

                        <th class="text-left px-4 py-3 text-gray-500 dark:text-gray-300">
                            Migration
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    <template x-for="item in versions" :key="item.version">

                        <tr>

                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white"
                                x-text="item.version.toUpperCase()">
                            </td>

                            <td class="px-4 py-3">

                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium"
                                    :class="item.status === 'current'
                                        ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300'
                                        : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300'"
                                    x-text="item.status"
                                ></span>

                            </td>

                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300"
                                x-text="formatDate(item.released_at)">
                            </td>

                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300"
                                x-text="item.sunset_at ? formatDate(item.sunset_at) : '—'">
                            </td>

                            <td class="px-4 py-3">

                                <span
                                    x-show="item.migration_to"
                                    class="text-blue-600 dark:text-blue-400 font-medium"
                                    x-text="item.migration_to ? item.migration_to.toUpperCase() : ''"
                                ></span>

                                <span
                                    x-show="!item.migration_to"
                                    class="text-gray-500 dark:text-gray-400"
                                >
                                    —
                                </span>

                            </td>

                        </tr>

                    </template>

                </tbody>

            </table>

        </div>

    </div>


    <!-- Deprecation Headers -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            V1 Deprecation Headers
        </h2>

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Requests to V1 automatically receive these headers so API clients know that migration is required.
        </p>

        <div class="space-y-2 font-mono text-sm">

            <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                X-API-Version: v1
            </div>

            <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-yellow-700 dark:text-yellow-300">
                X-API-Status: deprecated
            </div>

            <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                X-API-Latest-Version: v2
            </div>

            <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                Sunset: 2027-01-01T00:00:00Z
            </div>

            <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-blue-700 dark:text-blue-300">
                X-API-Migration: /api/v2/products
            </div>

        </div>

    </div>


    <!-- Health Check -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                System Health
            </h2>

            <span
                class="px-3 py-1 rounded-full text-xs font-semibold"
                :class="healthStatus === 'ok'
                    ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                    : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300'"
            >
                <span
                    x-text="healthStatus === 'ok'
                        ? '✓ Healthy'
                        : healthStatus === 'error'
                            ? '✗ Issues Detected'
                            : 'Checking...'"
                ></span>
            </span>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Database
                </p>

                <p
                    class="font-semibold"
                    :class="checks.database
                        ? 'text-green-600 dark:text-green-400'
                        : 'text-red-600 dark:text-red-400'"
                    x-text="checks.database ? 'Connected' : 'Disconnected'"
                ></p>

            </div>

            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Cache
                </p>

                <p
                    class="font-semibold"
                    :class="checks.cache
                        ? 'text-green-600 dark:text-green-400'
                        : 'text-red-600 dark:text-red-400'"
                    x-text="checks.cache ? 'Working' : 'Failed'"
                ></p>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

function dashboard() {

    return {

        currentVersion: 'v2',

        versions: [],

        loadingVersions: true,

        healthStatus: 'loading',

        checks: {
            database: false,
            cache: false
        },

        async init() {

            await Promise.all([
                this.fetchVersions(),
                this.fetchHealth()
            ]);

        },

        async fetchVersions() {

            this.loadingVersions = true;

            try {

                const response = await fetch('/api/versions', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch API versions.');
                }

                const data = await response.json();

                this.currentVersion = data.current_version;

                this.versions = data.versions || [];

            } catch (error) {

                console.error(error);

                this.versions = [];

            } finally {

                this.loadingVersions = false;

            }

        },

        async fetchHealth() {

            try {

                const response = await fetch('/api/health', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                this.healthStatus = data.status;

                this.checks = data.checks || {
                    database: false,
                    cache: false
                };

            } catch (error) {

                this.healthStatus = 'error';

            }

        },

        formatDate(date) {

            if (!date) {
                return '—';
            }

            return new Date(date).toLocaleDateString(
                'en-US',
                {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                }
            );

        }

    }

}

</script>

@endpush