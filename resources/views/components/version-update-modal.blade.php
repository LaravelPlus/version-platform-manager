@if(isset($version_update_data))
<div id="version-update-modal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-white">
                                What's New in Version {{ $version_update_data['version'] }}
                            </h3>
                            <p class="text-sm text-blue-100">
                                {{ $version_update_data['title'] }}
                            </p>
                        </div>
                    </div>
                    <button @click="closeModal" class="text-white hover:text-blue-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-4 max-h-96 overflow-y-auto">
                @if($version_update_data['markdown_content'])
                    <div class="prose prose-sm max-w-none" v-html="markdownContent"></div>
                @else
                    <div class="space-y-4">
                        @if($version_update_data['description'])
                            <div class="text-gray-700">
                                {{ $version_update_data['description'] }}
                            </div>
                        @endif
                        
                        @if($version_update_data['whats_new']->count() > 0)
                            <div class="space-y-3">
                                @foreach($version_update_data['whats_new'] as $item)
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-shrink-0 text-2xl">
                                            {{ $item->type_icon }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->type_label }}
                                            </div>
                                            <div class="text-sm text-gray-600 mt-1 whitespace-pre-line">
                                                {{ $item->content }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <button @click="skipVersion" class="text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">
                    Skip for now
                </button>
                <div class="flex space-x-3">
                    <button @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                    <button @click="markAsRead" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition-colors">
                        Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            isVisible: false,
            versionData: @json($version_update_data),
            markdownContent: '',
        }
    },
    mounted() {
        this.checkShouldShow();
        this.renderMarkdown();
    },
    methods: {
        async checkShouldShow() {
            try {
                const response = await fetch('/version-platform-manager/should-show-update', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.should_show) {
                    this.showModal();
                }
            } catch (error) {
                console.error('Error checking version update:', error);
            }
        },
        
        renderMarkdown() {
            if (this.versionData.markdown_content) {
                // Simple markdown rendering (you can use a proper markdown library)
                this.markdownContent = this.simpleMarkdownToHtml(this.versionData.markdown_content);
            }
        },
        
        simpleMarkdownToHtml(markdown) {
            return markdown
                .replace(/^### (.*$)/gim, '<h3 class="text-lg font-semibold text-gray-900 mb-2">$1</h3>')
                .replace(/^## (.*$)/gim, '<h2 class="text-xl font-semibold text-gray-900 mb-3">$1</h2>')
                .replace(/^# (.*$)/gim, '<h1 class="text-2xl font-bold text-gray-900 mb-4">$1</h1>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/`(.*?)`/g, '<code class="bg-gray-100 px-1 py-0.5 rounded text-sm">$1</code>')
                .replace(/\n/g, '<br>');
        },
        
        showModal() {
            this.isVisible = true;
            document.getElementById('version-update-modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        },
        
        closeModal() {
            this.isVisible = false;
            document.getElementById('version-update-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        },
        
        async markAsRead() {
            try {
                const response = await fetch('/version-platform-manager/mark-as-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        version: this.versionData.version
                    }),
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.closeModal();
                }
            } catch (error) {
                console.error('Error marking as read:', error);
            }
        },
        
        async skipVersion() {
            try {
                const response = await fetch('/version-platform-manager/skip', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        version: this.versionData.version
                    }),
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.closeModal();
                }
            } catch (error) {
                console.error('Error skipping version:', error);
            }
        }
    }
}).mount('#version-update-modal');
</script>
@endif 