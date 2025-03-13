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
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Batch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

<!-- START -->

            <dl class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                @livewire('voter-stats')
                @livewire('supervisor-stats')
                <div class="overflow-hidden pt-5 sm:pt-6 flex justify-between items-end space-x-4">
                    <button type="button" 
                        onclick="createUsers()"
                        wire:click="$emit('voterStatsUpdated')"
                        class="w-64 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Crear Usuarios
                    </button>
                    <button type="button" 
                        onclick="validateAllUsers()"
                        class="w-32 inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Validar
                    </button>
                </div>
                <div class="col-span-3">
                    <div id="toast" class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full transition-all duration-300 z-50 max-w-sm w-full shadow-lg mt-4">
                        <div class="flex items-center p-4 text-sm rounded-lg" role="alert">
                            <div id="toastMessage" class="flex-1 text-center"></div>
                        </div>
                    </div>
                </div>
            </dl>

        <!-- END -->
            <div class="mt-6 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-gray-50 border-b border-gray-200">
                    <div style="height: 600px;">
                        <div id="spreadsheet" class="w-full h-full"></div>
                    </div>
                </div>
                <script src="{{ asset('js/handsontable.full.min.js') }}"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        var container = document.getElementById('spreadsheet');
                        var hot;

                        function validateRUN(run) {
                            if (!run || run.trim() === '') return false;
                            
                            run = run.replace(/[.-]/g, '').toUpperCase();
                            if (run.length < 2) return false;
                            
                            let rut = run.slice(0, -1);
                            let dv = run.slice(-1);
                            
                            let sum = 0;
                            let multiplier = 2;
                            
                            for (let i = rut.length - 1; i >= 0; i--) {
                                sum += Number(rut[i]) * multiplier;
                                multiplier = multiplier === 7 ? 2 : multiplier + 1;
                            }
                            let expectedDv = 11 - (sum % 11);
                            expectedDv = expectedDv === 11 ? '0' : expectedDv === 10 ? 'K' : expectedDv.toString();
                            
                            return dv === expectedDv;
                        }

                        function validateEmail(email) {
                            if (!email || email.trim() === '') return false;
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            return emailRegex.test(email);
                        }
                        
                        function validateAllUsers() {
                            const data = hot.getData();
                            const dataLength = data.findIndex(row => 
                                row.every(cell => cell === null || cell === '')
                            );
                            
                            for (let rowIndex = 0; rowIndex < (dataLength === -1 ? data.length : dataLength); rowIndex++) {
                                const row = data[rowIndex];
                                const run = row[1];
                                const email = row[3];
                                const isValidRUN = validateRUN(run);
                                const isValidEmail = validateEmail(email);
                                const isRowValid = isValidRUN && isValidEmail;
                            
                                if(row[1] !== '') {
                                    hot.setCellMeta(rowIndex, 1, 'className', 
                                        isValidRUN ? 'monospace-cell' : 'monospace-cell text-red-500'
                                    );
                                }
                                
                                if(row[3] !== '') {
                                    hot.setCellMeta(rowIndex, 3, 'className',
                                        isValidEmail ? 'monospace-cell' : 'monospace-cell text-red-500'
                                    );
                                }
                                hot.setDataAtCell(rowIndex, 0, isRowValid);
                            }
                            hot.render();
                        }

                        function showToast(message, type = 'success') {
                            const toast = document.getElementById('toast');
                            const toastMessage = document.getElementById('toastMessage');
                            
                            toast.className = 'fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full transition-all duration-300 z-50 max-w-sm w-full shadow-lg mt-4';
                            if (type === 'success') {
                                toast.className += ' bg-green-50 text-green-800 border border-green-500 rounded-lg';
                            } else {
                                toast.className += ' bg-red-50 text-red-800 border border-red-500 rounded-lg';
                            }
                            
                            toastMessage.textContent = message;
                            toast.classList.remove('hidden');
                            
                            // Initial position (above viewport)
                            toast.style.transform = 'translateX(-50%) translateY(-100%)';
                            
                            // Slide down animation
                            requestAnimationFrame(() => {
                                toast.style.transform = 'translateX(-50%) translateY(0)';
                            });
                            
                            // Slide up and hide after delay
                            setTimeout(() => {
                                toast.style.transform = 'translateX(-50%) translateY(-100%)';
                                setTimeout(() => {
                                    toast.classList.add('hidden');
                                }, 300);
                            }, 3000);
                        }
                        
                        function createUsers() {
                            validateAllUsers();
                            const data = hot.getData();
                            const validUsers = data.filter((row, index) => {
                                return row[0] === true && 
                                       row[1] && row[2] && row[3];
                            }).map(row => ({
                                run: row[1],
                                name: row[2],
                                email: row[3]
                            }));

                            if (validUsers.length === 0) {
                                showToast('No hay usuarios válidos para crear', 'error');
                                return;
                            }

                            fetch('/users/batch', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({ users: validUsers })
                            })
                            .then(async response => {
                                if (!response.ok) {
                                    if (response.status === 400) {
                                        throw new Error('Los datos ingresados no son válidos');
                                    } else if (response.status === 409) {
                                        throw new Error('Algunos usuarios ya existen en el sistema');
                                    } else {
                                        throw new Error('Ha ocurrido un error interno');
                                    }
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    const rowsToRemove = hot.getData()
                                        .map((row, index) => row[0] === true ? index : -1)
                                        .filter(index => index !== -1)
                                        .reverse();
                                    
                                    rowsToRemove.forEach(index => {
                                        hot.alter('remove_row', index);
                                    });
                                    
                                    showToast('Usuarios creados exitosamente');
                                    // New Livewire 3.0 dispatch syntax
                                    Livewire.dispatch('voterStatsUpdated');
                                }
                            })
                            .catch(error => {
                                showToast(error.message, 'error');
                            });
                        }
                        
                        hot = new Handsontable(container, {
                            data: [
                                [false, '33333333-3', 'Nelson', 'nh@gmail.com'],
                                [false, '12046474-3', 'Nelson', 'nh@gmail'],
                                [false, '12046474-5', 'Nelson', 'nh@gmail.com'],
                            ],
                            colHeaders: ['', 'RUN', 'Nombre', 'Correo electrónico'],
                            headerClassName: 'sans-serif-header',
                            columns: [
                                { type: 'checkbox', className: 'htCenter' },
                                { type: 'text', className: 'monospace-cell' },
                                { type: 'text', className: 'monospace-cell' },
                                { type: 'text', className: 'monospace-cell' }
                            ],
                            colWidths: [8, null, null, null],
                            rowHeaders: false,
                            minCols: 3,
                            minRows: 1,
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
