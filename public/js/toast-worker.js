let messageQueue = [];
let isProcessing = false;
let isAnimating = false;

self.onmessage = function(e) {
    if (e.data.action === 'ready') {
        isAnimating = false;
        processQueue();
        return;
    }

    messageQueue.push(e.data);
    if (!isProcessing && !isAnimating) {
        processQueue();
    }
};

async function processQueue() {
    if (messageQueue.length === 0 || isAnimating) {
        isProcessing = false;
        return;
    }
    
    isProcessing = true;
    isAnimating = true;
    const { message, type } = messageQueue.shift();
    
    self.postMessage({ action: 'show', message, type });

    await new Promise(resolve => setTimeout(resolve, 3000));

    self.postMessage({ action: 'hide' });
}