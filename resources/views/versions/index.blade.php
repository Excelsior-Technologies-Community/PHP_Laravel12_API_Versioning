@extends('layouts.app')

@section('content')

<div class="px-4 sm:px-0">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    API Versions
                </h1>

                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Manage API versions, lifecycle status, and migration information.
                </p>
            </div>

            <a href="{{ url('/api/versions') }}"
                target="_blank"
                class="inline-flex items-center justify-center px-4 py-2
                       bg-blue-600 hover:bg-blue-700 text-white
                       rounded-lg text-sm font-medium transition">

                View JSON API

            </a>

        </div>
    </div>

    <!-- Version Cards -->
    <div
        x-data="apiVersionsPage()"
        x-init="init()"
        class="space-y-6"
    >

        <!-- Loading -->
        <div
            x-show="loading"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm
                   border border-gray-200 dark:border-gray-700 p-8 text-center"
        >
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>

            <p class="mt-3 text-gray-500 dark:text-gray-400">
                Loading API versions...
            </p>
        </div>

        <!-- Error -->
        <div
            x-show="error"
            x-cloak
            class="bg-red-50 dark:bg-red-900/20
                   border border-red-200 dark:border-red-800
                   rounded-xl p-5"
        >
            <p class="text-red-700 dark:text-red-400" x-text="error"></p>
        </div>

        <!-- Versions -->
        <template x-for="item in versions" :key="item.version">

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm
                       border border-gray-200 dark:border-gray-700 overflow-hidden"
            >

                <!-- Card Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div class="flex items-center gap-4">

                            <div
                                class="w-12 h-12 rounded-xl flex items-center justify-center
                                text-lg font-bold"
                                :class="item.version === 'v1'
                                    ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                    : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'"
                            >
                                <span x-text="item.version.toUpperCase()"></span>
                            </div>

                            <div>

                                <h2
                                    class="text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    API <span x-text="item.version.toUpperCase()"></span>
                                </h2>

                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                    x-text="item.message"
                                ></p>

                            </div>

                        </div>

                        <!-- Status -->
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full
                                   text-sm font-medium w-fit"
                            :class="item.status === 'current'
                                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'"
                            x-text="item.status.toUpperCase()"
                        ></span>

                    </div>

                </div>

                <!-- Information -->
                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div>
                            <p class="text-xs uppercase tracking-wide
                                      text-gray-500 dark:text-gray-400">
                                Released
                            </p>

                            <p
                                class="mt-1 font-semibold text-gray-900 dark:text-white"
                                x-text="item.released_at"
                            ></p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide
                                      text-gray-500 dark:text-gray-400">
                                Sunset
                            </p>

                            <p
                                class="mt-1 font-semibold text-gray-900 dark:text-white"
                                x-text="item.sunset_at || 'No sunset date'"
                            ></p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide
                                      text-gray-500 dark:text-gray-400">
                                Migration
                            </p>

                            <p
                                class="mt-1 font-semibold text-gray-900 dark:text-white"
                                x-text="item.migration_to
                                    ? item.migration_to.toUpperCase()
                                    : '—'"
                            ></p>
                        </div>

                    </div>

                    <!-- Endpoint -->
                    <div class="mt-6">

                        <p class="text-xs uppercase tracking-wide
                                  text-gray-500 dark:text-gray-400 mb-2">
                            Endpoint
                        </p>

                        <a
                            :href="item.endpoint"
                            target="_blank"
                            class="block px-4 py-3 rounded-lg
                                   bg-gray-100 dark:bg-gray-900
                                   text-sm text-blue-600 dark:text-blue-400
                                   break-all hover:underline"
                            x-text="item.endpoint"
                        ></a>

                    </div>

                    <!-- Features -->
                    <div class="mt-6">

                        <p class="text-sm font-semibold
                                  text-gray-900 dark:text-white mb-3">
                            Features
                        </p>

                        <div class="flex flex-wrap gap-2">

                            <template x-for="feature in item.features" :key="feature">

                                <span
                                    class="px-3 py-1 rounded-full text-xs
                                           bg-green-100 text-green-700
                                           dark:bg-green-900/30
                                           dark:text-green-400"
                                    x-text="feature"
                                ></span>

                            </template>

                        </div>

                    </div>

                    <!-- Removed Features -->
                    <template x-if="item.removed_features.length > 0">

                        <div class="mt-6">

                            <p class="text-sm font-semibold
                                      text-gray-900 dark:text-white mb-3">
                                Removed / Not Available
                            </p>

                            <div class="flex flex-wrap gap-2">

                                <template
                                    x-for="feature in item.removed_features"
                                    :key="feature"
                                >

                                    <span
                                        class="px-3 py-1 rounded-full text-xs
                                               bg-red-100 text-red-700
                                               dark:bg-red-900/30
                                               dark:text-red-400"
                                        x-text="feature"
                                    ></span>

                                </template>

                            </div>

                        </div>

                    </template>

                    <!-- Migration -->
                    <template x-if="item.migration_endpoint">

                        <div class="mt-6">

                            <a
                                :href="item.migration_endpoint"
                                target="_blank"
                                class="inline-flex items-center px-4 py-2
                                       bg-purple-600 hover:bg-purple-700
                                       text-white rounded-lg text-sm
                                       font-medium transition"
                            >
                                Migrate to
                                <span
                                    class="ml-1"
                                    x-text="item.migration_to.toUpperCase()"
                                ></span>
                            </a>

                        </div>

                    </template>

                </div>

            </div>

        </template>

    </div>

</div>

@endsection

@push('scripts')

<script>
function apiVersionsPage() {

    return {

        loading: true,

        error: '',

        versions: [],

        async init() {

            try {

                const response = await fetch('/api/versions', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(
                        `HTTP error: ${response.status}`
                    );
                }

                const data = await response.json();

                this.versions = data.versions || [];

            } catch (error) {

                console.error(error);

                this.error = 'Unable to load API version information.';

            } finally {

                this.loading = false;

            }

        }

    };
}
</script>

@endpush