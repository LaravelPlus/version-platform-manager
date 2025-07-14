@props(['id' => 'modal-wrapper', 'show' => false, 'autoShow' => false, 'maxWidth' => 'max-w-2xl'])

<div id="{{ $id }}" class="fixed inset-0 overflow-y-auto {{ $show || $autoShow ? 'block' : 'hidden' }}" style="z-index: 9999;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" style="z-index: 9998;" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $maxWidth }} sm:w-full" style="z-index: 9999;">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                {{ $slot }}
            </div>
            
            @if(isset($footer))
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showModal() {
    document.getElementById('{{ $id }}').classList.remove('hidden');
    document.getElementById('{{ $id }}').classList.add('block');
}

function closeModal() {
    document.getElementById('{{ $id }}').classList.remove('block');
    document.getElementById('{{ $id }}').classList.add('hidden');
}

// Auto-show modal after page load (optional)
document.addEventListener('DOMContentLoaded', function() {
    @if($autoShow)
        setTimeout(showModal, 1000);
    @endif
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('{{ $id }}');
    if (event.target === modal) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Make functions globally available
window.showModal = showModal;
window.closeModal = closeModal;
</script> 