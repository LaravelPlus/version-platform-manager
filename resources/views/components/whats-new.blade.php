@php
    $style = $style ?? config('version-platform-manager.whats_new_style', 'tailwind');
@endphp

{{-- Tailwind Modal --}}
@if (!defined('TAILWIND_LOADED'))
    <script src="https://cdn.tailwindcss.com"></script>
    @php define('TAILWIND_LOADED', true); @endphp
@endif

<div id="whats-new-component">
    @if(!session()->has('rdp_on'))
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="text-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4" />
                                </svg>
                            </div>
                            
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                @{{ title }}
                            </h2>
                            
                            <div class="text-left mt-6 space-y-6">
                                <!-- System Update Section -->
                                <div v-if="latestVersion" class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                    <h3 class="text-lg font-semibold text-blue-900 mb-2">
                                        System Update from Version @{{ userVersion }} to @{{ latestVersion.version }}
                                    </h3>
                                    <p class="text-blue-800">@{{ latestVersion.description || 'We\'ve made significant improvements to enhance your experience!' }}</p>
                                </div>

                                <!-- What's New Features -->
                                <div v-if="whatsNew.length > 0" class="space-y-4">
                                    <div v-for="(features, version) in groupedFeatures" :key="version" class="bg-gray-50 p-4 rounded-lg">
                                        <h5 class="text-lg font-semibold text-gray-900 mb-3">🔒 What Does This Mean for You?</h5>
                                        <ul class="space-y-2">
                                            <li v-for="feature in features" :key="feature.id" class="flex items-start space-x-2">
                                                <span class="text-lg">@{{ feature.type_icon || '📝' }}</span>
                                                <div>
                                                    <strong class="text-gray-900">@{{ feature.title }}:</strong>
                                                    <span class="text-gray-700">@{{ feature.content }}</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Signature Section -->
                                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                    <p class="text-green-800 mb-2">We're excited for you to experience these updates.</p>
                                    <p class="text-green-800">If you have any questions or comments, please don't hesitate to reach out.</p>
                                    <p class="text-green-900 font-semibold mt-3" v-html="signature"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="closeModal" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Got it!
                        </button>
                        <button @click="markAsSeen" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Don't show again
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            showModal: {{ isset($autoShow) && $autoShow ? 'true' : 'false' }},
            title: `{!! $title ?? '🎉 Exciting News: 🎉 <br> Major Updates to Improve Your Experience!' !!}`,
            userVersion: '{{ $userVersion->version ?? "1.0.0" }}',
            latestVersion: @json($latestVersion ?? null),
            whatsNew: @json($whatsNew ?? []),
            signature: `{!! config('version-platform-manager.whats_new_signature') !!}`,
            isLoading: false
        }
    },
    computed: {
        groupedFeatures() {
            const grouped = {};
            this.whatsNew.forEach(feature => {
                const version = feature.platform_version?.version || 'Unknown';
                if (!grouped[version]) {
                    grouped[version] = [];
                }
                grouped[version].push(feature);
            });
            return grouped;
        }
    },
    methods: {
        closeModal() {
            this.showModal = false;
        },
        async markAsSeen() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route("version-platform-manager.mark-seen") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        version_id: this.latestVersion?.id
                    })
                });
                
                if (response.ok) {
                    this.closeModal();
                    // Optionally show a success message
                    console.log('Marked as seen');
                }
            } catch (error) {
                console.error('Error marking as seen:', error);
            } finally {
                this.isLoading = false;
            }
        },
        showWhatsNew() {
            this.showModal = true;
        }
    },
    mounted() {
        // Auto-show modal if autoShow is true
        if ({{ isset($autoShow) && $autoShow ? 'true' : 'false' }}) {
            this.showModal = true;
        }
        
        // Listen for custom events to show modal
        window.addEventListener('show-whats-new', () => {
            this.showModal = true;
        });
    }
}).mount('#whats-new-component');
</script> 