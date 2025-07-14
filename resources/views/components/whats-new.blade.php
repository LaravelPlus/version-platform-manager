@php
    $style = $style ?? config('version-platform-manager.whats_new_style', 'tailwind');
@endphp

@if ($style === 'bootstrap')
    {{-- Bootstrap 5 Modal --}}
    <div class="modal fade show" id="whatsNewModal" tabindex="-1" aria-labelledby="whatsNewModalLabel" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="whatsNewModalLabel">🎉 Exciting News: 🎉</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <strong>Major Updates to Improve Your Experience!</strong>
                    <p>We're excited for you to experience these updates.<br>
                    If you have any questions or comments, please don't hesitate to reach out.</p>
                    <p class="mt-3 mb-0"><small>Best regards,<br>Kitio Internacional d.o.o.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="closeModal()">Got it!</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function closeModal() {
            document.getElementById('whatsNewModal').style.display = 'none';
        }
    </script>
@else
    {{-- Tailwind Modal (existing code) --}}
    @if (!defined('TAILWIND_LOADED'))
        <script src="https://cdn.tailwindcss.com"></script>
        @php define('TAILWIND_LOADED', true); @endphp
    @endif
    <div>
        @if(!session()->has('rdp_on'))
            <div id="whats-new-modal" class="fixed inset-0 z-50 overflow-y-auto {{ isset($autoShow) && $autoShow ? 'block' : 'hidden' }}" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

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
                                    {!! $title ?? '🎉 Exciting News: 🎉 <br> Major Updates to Improve Your Experience!' !!}
                                </h2>
                                
                                <div class="text-left mt-6 space-y-6">
                                    @if(isset($latestVersion) && $latestVersion)
                                        <!-- System Update -->
                                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                            <h3 class="text-lg font-semibold text-blue-900 mb-2">
                                                System Update from Version {{ $userVersion->version ?? '1.0.0' }} to {{ $latestVersion->version }}
                                            </h3>
                                            <p class="text-blue-800">{{ $latestVersion->description ?: 'We\'ve made significant improvements to enhance your experience!' }}</p>
                                        </div>
                                    @endif

                                    @if(isset($whatsNew) && $whatsNew->isNotEmpty())
                                        @foreach($whatsNew->groupBy('platformVersion.version') as $version => $features)
                                            @if($version !== ($userVersion->version ?? '1.0.0'))
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <h5 class="text-lg font-semibold text-gray-900 mb-3">🔒 What Does This Mean for You?</h5>
                                                    <ul class="space-y-2">
                                                        @foreach($features as $feature)
                                                            <li class="flex items-start space-x-2">
                                                                <span class="text-lg">{{ $feature->type_icon ?? '📝' }}</span>
                                                                <div>
                                                                    <strong class="text-gray-900">{{ $feature->title }}:</strong>
                                                                    <span class="text-gray-700">{{ $feature->content }}</span>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                @if(!$loop->last)
                                                    <hr class="border-gray-200">
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif

                                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                        <p class="text-green-800 mb-2">We're excited for you to experience these updates.</p>
                                        <p class="text-green-800">If you have any questions or comments, please don't hesitate to reach out.</p>
                                        <p class="text-green-900 font-semibold mt-3">{!! config('version-platform-manager.whats_new_signature') !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                                {{ __('Go to dashboard') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
    function closeModal() {
        document.getElementById('whats-new-modal').classList.add('hidden');
    }
    </script>
@endif 