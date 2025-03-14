<x-app-layout>
    <x-slot name="custom_styles">
        <link rel="stylesheet" href="{{ asset('css/handsontable.full.min.css') }}">
        <style>
            .sans-serif-header {
                font-family: 'Figtree', sans-serif !important;
                font-weight: 600;
            }
            .monospace-cell {
                font-family: monospace !important;
            }
            .handsontable th {
                font-family: 'Figtree', sans-serif !important;
                background-color: rgb(249 250 251) !important;
                color: rgb(17 24 39) !important;
                font-size: 0.875rem !important;
                line-height: 1.25rem !important;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Batch upload users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @livewire('voter-stats')
                @livewire('supervisor-stats')
                
                <div class="col-span-3">
                    <div id="toast" class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full transition-all duration-300 z-50 max-w-sm w-full shadow-lg mt-4">
                        <div class="flex items-center p-4 text-sm rounded-lg" role="alert">
                            <div id="toastMessage" class="flex-1 text-center"></div>
                        </div>
                    </div>
                </div>
            </dl>
            <div class="mt-6 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-gray-50 border-b border-gray-200">
                    <div class="overflow-hidden py-5 sm:py-6">
                        <div class="flex flex-row-reverse justify-start space-x-4 space-x-reverse">
                            <button type="button" 
                                onclick="copyCurrentData()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="hidden sm:block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                </svg>
                                {{ __('Copy to clipboard') }}
                            </button>
                            <button type="button" 
                                onclick="validateAllUsers()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="hidden sm:block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Validate') }}
                            </button>
                            <button type="button" 
                                onclick="createUsers()"
                                wire:click="$emit('voterStatsUpdated')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="hidden sm:block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                </svg>
                                {{ __('Create users') }}
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-rows-[minmax(300px,_1fr)]">
                        <div id="spreadsheet" class="w-full h-full"></div>
                    </div>
                </div>
                <script src="{{ asset('js/handsontable.full.min.js') }}"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        var container = document.getElementById('spreadsheet');
                        var hot;
                        let validationWorker;
                        let toastWorker;

                        function validateAllUsers() {
                            return new Promise((resolve, reject) => {
                                if (!validationWorker) {
                                    validationWorker = new Worker('/js/validation-worker.js');
                                }
                        
                                const data = hot.getData();
                                
                                // Remove empty rows
                                const nonEmptyRows = data.filter(row => 
                                    !row.every(cell => cell === null || cell === '')
                                );

                                hot.loadData(nonEmptyRows);
                                
                                validationWorker.onmessage = function(e) {
                                    const results = e.data;
                                    console.log({results});
                                        
                                    results.forEach(result => {
                                        if(data[result.index][0] !== '') {
                                            if(result.isRUNAvailable) {
                                                hot.setCellMeta(result.index, 0, 'className', 
                                                    result.isValidRUN ? 'monospace-cell' : 'monospace-cell text-amber-500'
                                                );
                                            } else {
                                                hot.setCellMeta(result.index, 0, 'className', 'monospace-cell text-red-500');
                                            }
                                        }
                                        
                                        if(data[result.index][2] !== '') {
                                            if(result.isEmailAvailable) {
                                                hot.setCellMeta(result.index, 2, 'className',
                                                    result.isValidEmail ? 'monospace-cell' : 'monospace-cell text-amber-500'
                                                );
                                            } else {
                                                hot.setCellMeta(result.index, 2, 'className', 'monospace-cell text-red-500');
                                            }
                                        }
                                    });
                                    
                                    hot.render();
                                    resolve(results);
                                };
                            
                                validationWorker.onerror = function(error) {
                                    reject(error);
                                };
                            
                                validationWorker.postMessage(nonEmptyRows);
                            });
                        }
                        
                        async function createUsers() {
                            try {
                                const validationResults = await validateAllUsers();
                                const data = hot.getData();
                                const validUsers = data.filter((row, index) => {
                                    const result = validationResults.find(r => r.index === index);
                                    return row[0] && row[1] && row[2] && 
                                           result && result.isValidRUN && result.isValidEmail &&
                                           result.isRUNAvailable && result.isEmailAvailable;
                                }).map(row => ({
                                    run: row[0],
                                    name: row[1],
                                    email: row[2]
                                }));
                        
                                if (validUsers.length === 0) {
                                    showToast('{{ __("There are no valid users to create") }}.', 'error');
                                    return;
                                }

                                const response = await fetch('/users/batch', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({ users: validUsers })
                                });
                                if (!response.ok) {
                                    if (response.status === 400) {
                                        throw new Error('{{ __("The data entered is not valid") }}.');
                                    } else if (response.status === 409) {
                                        throw new Error('{{ __("Some users already exist in the system") }}.');
                                    } else {
                                        throw new Error('{{ __("An internal error has occurred") }}.');
                                    }
                                }
                                const result = await response.json();
                                if (result.success) {
                                    const successIndices = validUsers.map((_, index) => {
                                        const row = data.findIndex(r => 
                                            r[0] === validUsers[index].run &&
                                            r[1] === validUsers[index].name &&
                                            r[2] === validUsers[index].email
                                        );
                                        return row;
                                    }).filter(index => index !== -1)
                                    .sort((a, b) => b - a);
                                    
                                    successIndices.forEach(index => {
                                        hot.alter('remove_row', index);
                                    });
                                    
                                    showToast('{{ __("Users created successfully") }}.');
                                    Livewire.dispatch('voterStatsUpdated');
                                }
                            } catch (error) {
                                showToast(error.message, 'error');
                            }
                        }
                    
                        function showToast(message, type = 'success') {
                            if (!toastWorker) {
                                toastWorker = new Worker('/js/toast-worker.js');
                                toastWorker.onmessage = handleToastWorkerMessage;
                            }
                            
                            toastWorker.postMessage({ message, type });
                        }
                    
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

                        async function copyCurrentData() {
                            try {
                                const validationResults = await validateAllUsers();
                                const data = hot.getData();
                                const headers = [...hot.getColHeader(), ''];
                                
                                const formattedData = data.map((row, index) => {
                                    const result = validationResults.find(r => r.index === index);
                                    if (!row[0] && !row[1] && !row[2]) return null;
                                    
                                    let status = '';
                                    if (result) {
                                        if (!result.isValidRUN) status += '{{ __("Invalid RUN") }}. ';
                                        if (!result.isRUNAvailable) status += '{{ __("RUN already registered") }}. ';
                                        if (!result.isValidEmail) status += '{{ __("Invalid e-mail") }}. ';
                                        if (!result.isEmailAvailable) status += '{{ __("E-mail already registered") }}. ';
                                        status = status.trim() || '';
                                    }
                                    
                                    return [...row, status].map(cell => cell || '').join('\t');
                                }).filter(row => row !== null);
                                
                                const csvContent = [headers.join('\t'), ...formattedData].join('\n');
                                
                                await navigator.clipboard.writeText(csvContent);
                                showToast('{{ __("Data copied to clipboard") }}.');
                            } catch (error) {
                                showToast('{{ __("Could not copy to clipboard") }}.', 'error');
                            }
                        }
                        
                        hot = new Handsontable(container, {
                            data: [
                                ['11111111-1', 'Nelson', '123@gmail.com'],
                                ['22222222-2', 'Nelson', '234@gmail.com'],
                                ['33333333-3', 'Nelson', '345@gmail.com'],
                                ['44444444-4', 'Nelson', '456@gmail.com'],
                                ['55555555-5', 'Nelson', '567@gmail.com'],
                                ['12046474-4', 'Nelson', 'nelson@hereveri.cl'],
                            ],
                            colHeaders: ['{{ __("RUN") }}', '{{ __("Name") }}', '{{ __("Email") }}'],
                            headerClassName: 'sans-serif-header',
                            columns: [
                                { type: 'text', className: 'monospace-cell' },
                                { type: 'text', className: 'monospace-cell' },
                                { type: 'text', className: 'monospace-cell' }
                            ],
                            colWidths: [null, null, null],
                            rowHeaders: false,
                            minCols: 3,
                            minRows: 5,
                            minSpareRows: 1,
                            width: '100%',
                            height: '100%',
                            stretchH: 'all'
                        });

                        // Make function available globally
                        window.validateAllUsers = validateAllUsers;
                        window.createUsers = createUsers;
                        window.copyCurrentData = copyCurrentData;
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
