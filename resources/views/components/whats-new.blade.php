<div>
    @if(!session()->has('rdp_on'))
        <div class="modal modal-blur fade show" id="modal-success" tabindex="-1"
             style="{{ $autoShow ? 'display: block;' : '' }}" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-status bg-success"></div>
                    <div class="modal-body text-center py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M9 12l2 2l4 -4"/>
                        </svg>
                        <h2 id="what-is-tabler mb-5">{!! $title !!}</h2>
                        <div class="text-start mt-3">

                            @if($latestVersion)
                                <!-- System Update -->
                                <h3>System Update from Version {{ $userVersion->version }} to {{ $latestVersion->version }}</h3>
                                <p>{{ $latestVersion->description ?: 'We\'ve made significant improvements to enhance your experience!' }}</p>
                            @endif

                            @foreach($whatsNew->groupBy('platformVersion.version') as $version => $features)
                                @if($version !== $userVersion->version)
                                    <h5>🔒 What Does This Mean for You?</h5>
                                    <ul>
                                        @foreach($features as $feature)
                                            <li>
                                                <strong>{{ $feature->type_icon }} {{ $feature->title }}:</strong>
                                                {{ $feature->content }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    @if(!$loop->last)
                                        <hr>
                                    @endif
                                @endif
                            @endforeach

                            <p>We're excited for you to experience these updates. <br>If you have any questions or comments, please don't hesitate to reach out.</p>
                            <p><strong>Best regards,<br>Kitio Internacional d.o.o.</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <form action="{{ route('version-platform-manager.mark-seen') }}" method="post">
                                        <input type="hidden" name="version" value="{{ $latestVersion->version ?? '' }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('Go to dashboard') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 