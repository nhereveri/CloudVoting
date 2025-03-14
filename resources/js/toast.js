function handleToastWorkerMessage(e) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const { action, message, type } = e.data;

    if (action === 'show') {
        // Reset any ongoing transitions
        toast.style.transition = 'none';
        toast.style.transform = 'translateX(-50%) translateY(-100%)';
        
        // Setup toast appearance
        toast.className = 'fixed top-0 left-1/2 transform -translate-x-1/2 z-50 max-w-sm w-full shadow-lg mt-4';
        
        switch (type) {
            case 'success':
                toast.className += ' bg-green-50 text-green-800 border border-green-500 rounded-lg';
                break;
            case 'warning':
                toast.className += ' bg-amber-50 text-amber-800 border border-amber-500 rounded-lg';
                break;
            case 'error':
                toast.className += ' bg-red-50 text-red-800 border border-red-500 rounded-lg';
                break;
        }
        
        toastMessage.textContent = message;
        toast.classList.remove('hidden');
        
        // Force reflow to ensure transition works
        void toast.offsetWidth;
        
        // Re-enable transitions and animate down
        toast.style.transition = 'transform 300ms ease-out';
        toast.style.transform = 'translateX(-50%) translateY(0)';
        
    } else if (action === 'hide') {
        toast.style.transition = 'transform 300ms ease-in';
        toast.style.transform = 'translateX(-50%) translateY(-100%)';
        
        // Wait for hide animation to complete before hiding element
        toast.addEventListener('transitionend', () => {
            toast.classList.add('hidden');
            // Signal the worker that we're ready for the next toast
            toastWorker.postMessage({ action: 'ready' });
        }, { once: true });
    }
}

let toastWorker;

export function showToast(message, type = 'success') {
    if (!toastWorker) {
        toastWorker = new Worker('/js/toast-worker.js');
        toastWorker.onmessage = handleToastWorkerMessage;
    }
    
    toastWorker.postMessage({ message, type });
}