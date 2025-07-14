@props(['id' => 'announcement-modal', 'show' => false, 'autoShow' => false])

<div id="{{ $id }}" class="fixed inset-0 z-50 overflow-y-auto {{ $show || $autoShow ? 'block' : 'hidden' }}" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                        🎉 Exciting News: 🎉 <br> Major Updates to Improve Your Experience!
                    </h2>
                    
                    <div class="text-left mt-6 space-y-6">
                        <!-- Include the whats-new component content here -->
                        <x-version-platform-manager::whats-new :autoShow="false"></x-version-platform-manager::whats-new>
                        
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                            <p class="text-green-800 mb-2">We're excited for you to experience these updates.</p>
                            <p class="text-green-800">If you have any questions or comments, please don't hesitate to reach out.</p>
                            <p class="text-green-900 font-semibold mt-3">Best regards,<br>Kitio Internacional d.o.o.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeAnnouncementModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Got it!
                </button>
                <button type="button" onclick="closeAnnouncementModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showAnnouncementModal() {
    document.getElementById('{{ $id }}').classList.remove('hidden');
    document.getElementById('{{ $id }}').classList.add('block');
}

function closeAnnouncementModal() {
    document.getElementById('{{ $id }}').classList.remove('block');
    document.getElementById('{{ $id }}').classList.add('hidden');
}

// Auto-show modal after page load (optional)
document.addEventListener('DOMContentLoaded', function() {
    // Uncomment the line below if you want the modal to show automatically
    // setTimeout(showAnnouncementModal, 1000);
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('{{ $id }}');
    if (event.target === modal) {
        closeAnnouncementModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAnnouncementModal();
    }
});
</script> 