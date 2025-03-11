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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<!-- START -->
            <!--h3 class="text-base font-semibold text-gray-900">Last 30 days</h3-->

            <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
                    <dt>
                        <div class="absolute rounded-md bg-indigo-500 p-3">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        </div>
                        <p class="ml-16 truncate text-sm font-medium text-gray-500">{{ __('Number of voters') }}</p>
                    </dt>
                    <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                        <p class="text-2xl font-semibold text-gray-900">71,897</p>
                        <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                            <div class="text-sm">
                                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">View all<span class="sr-only"> Total Subscribers stats</span></a>
                            </div>
                        </div>
                    </dd>
                </div>
                <!--
                <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
                    <dt>
                        <div class="absolute rounded-md bg-indigo-500 p-3">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.981l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 1.615a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V8.844a2.25 2.25 0 0 1 1.183-1.981l7.5-4.039a2.25 2.25 0 0 1 2.134 0l7.5 4.039a2.25 2.25 0 0 1 1.183 1.98V19.5Z" />
                        </svg>
                        </div>
                        <p class="ml-16 truncate text-sm font-medium text-gray-500">Avg. Open Rate</p>
                    </dt>
                    <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                        <p class="text-2xl font-semibold text-gray-900">58.16%</p>
                        <p class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                        <svg class="size-5 shrink-0 self-center text-green-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.08-1.04l5.25-5.5a.75.75 0 0 1 1.08 0l5.25 5.5a.75.75 0 1 1-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only"> Increased by </span>
                        5.4%
                        </p>
                        <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">View all<span class="sr-only"> Avg. Open Rate stats</span></a>
                        </div>
                        </div>
                    </dd>
                </div>
                <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
                    <dt>
                        <div class="absolute rounded-md bg-indigo-500 p-3">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672ZM12 2.25V4.5m5.834.166-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243-1.59-1.59" />
                        </svg>
                        </div>
                        <p class="ml-16 truncate text-sm font-medium text-gray-500">Avg. Click Rate</p>
                    </dt>
                    <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                        <p class="text-2xl font-semibold text-gray-900">24.57%</p>
                        <p class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                        <svg class="size-5 shrink-0 self-center text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v10.638l3.96-4.158a.75.75 0 1 1 1.08 1.04l-5.25 5.5a.75.75 0 0 1-1.08 0l-5.25-5.5a.75.75 0 1 1 1.08-1.04l3.96 4.158V3.75A.75.75 0 0 1 10 3Z" clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only"> Decreased by </span>
                        3.2%
                        </p>
                        <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">View all<span class="sr-only"> Avg. Click Rate stats</span></a>
                        </div>
                        </div>
                    </dd>
                </div>
                -->
            </dl>

        <!-- END -->

            <div class="mt-6 p-6 lg:p-8 bg-white border-b border-gray-200">
                <div class="grid grid-cols-3 gap-4">

                    <div class="col-span-3 flex justify-end space-x-4">
                        <button type="button" 
                            onclick="createUsers()"
                            class="w-32 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Crear Usuarios
                        </button>
                        <button type="button" 
                            onclick="validateAllUsers()"
                            class="w-32 inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Validar
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div class="col-span-3 overflow-hidden">
                        <div id="toast" class="hidden transform transition-all duration-300 opacity-0">
                            <div class="flex items-center p-4 text-sm rounded-lg" role="alert">
                                <div id="toastMessage"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden shadow-xl sm:rounded-lg">
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
                            
                            toast.className = 'transform transition-all duration-300';
                            if (type === 'success') {
                                toast.className += ' bg-green-50 text-green-800 border border-green-500 rounded-lg';
                            } else {
                                toast.className += ' bg-red-50 text-red-800 border border-red-500 rounded-lg';
                            }
                            
                            toastMessage.textContent = message;
                            toast.classList.remove('hidden', 'opacity-0');
                            
                            setTimeout(() => {
                                toast.classList.add('opacity-0');
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
