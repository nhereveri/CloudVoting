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
                                onclick="validateAllUsers()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Validar
                            </button>
                            <button type="button" 
                                onclick="createUsers()"
                                wire:click="$emit('voterStatsUpdated')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Crear Usuarios
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
                                    showToast('No hay usuarios válidos para crear.', 'error');
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
                                        throw new Error('Los datos ingresados no son válidos.');
                                    } else if (response.status === 409) {
                                        throw new Error('Algunos usuarios ya existen en el sistema.');
                                    } else {
                                        throw new Error('Ha ocurrido un error interno.');
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
                                    
                                    showToast('Usuarios creados exitosamente.');
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
                        
                        hot = new Handsontable(container, {
                            colHeaders: ['RUN', 'Nombre', 'Correo electrónico'],
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
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
