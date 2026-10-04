@extends('layouts.app')

@section('content')

    <!-- Success & Error Alerts -->
    @if(session('success'))
        <div id="toast-alert" class="fixed top-8 right-8 bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 z-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-lg font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div id="toast-alert-error" class="fixed top-8 right-8 bg-rose-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 z-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="text-lg font-semibold">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Live Board Section -->
    <div class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm mt-6 mb-6">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Live Board Status <span
                    class="text-xl text-slate-500 font-medium">(ඉතිරි කොටු)</span></h2>

            <!-- Status Indicators (Text size එක text-base කළා, රවුම් w-4 h-4 කළා) -->
            <div class="flex gap-6 text-base font-semibold">
                <span class="flex items-center gap-2 text-green-600">
                    <div class="w-4 h-4 rounded-full bg-green-500"></div> Available
                </span>
                <span class="flex items-center gap-2 text-amber-500">
                    <div class="w-4 h-4 rounded-full bg-amber-400"></div> Ending Soon
                </span>
                <span class="flex items-center gap-2 text-slate-400">
                    <div class="w-4 h-4 rounded-full bg-slate-200"></div> Sold Out
                </span>
            </div>
        </div>

        <!-- 10x10 Grid (Gap එක gap-3 කළා) -->
        <div class="grid grid-cols-10 gap-3">
            <button
                class="bg-green-50 border border-green-200 rounded-xl py-3 px-2 flex flex-col items-center justify-center hover:bg-green-100 transition-colors cursor-pointer shadow-sm hover:shadow-md">
                <span class="text-3xl font-bold text-green-700">1</span>
                <span class="text-sm font-bold text-green-700 bg-green-200/50 px-3 py-1 rounded-full mt-1.5">20 left</span>
            </button>

        </div>
    </div>

    <div class="flex flex-col gap-6">
        <!-- Top Section: Summary Cards -->
        <div class="grid grid-cols-4 gap-6 mb-6">

            <!-- 1. Total Revenue Card -->
            <div
                class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm flex items-center gap-6 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-9 h-9">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                    </svg>
                </div>
                <div>
                    <p class="text-base font-semibold text-slate-500 uppercase tracking-wide">Total Revenue</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">Rs. {{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>

            <!-- 2. Cash Collected Card  -->
            <div
                class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm flex items-center gap-6 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-9 h-9">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-base font-semibold text-slate-500 uppercase tracking-wide">Cash Collected</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">Rs. {{ number_format($cashCollected, 2) }}</h3>
                </div>
            </div>

            <!-- 3. Total Naya Card  -->
            <div
                class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm flex items-center gap-6 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-9 h-9">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-base font-semibold text-slate-500 uppercase tracking-wide">Pending Payments</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">Rs. {{ number_format($totalNaya, 2) }}</h3>
                </div>
            </div>

            <!-- 4. Total Boxes Card -->
            <div
                class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm flex items-center gap-6 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-9 h-9">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                </div>
                <div>
                    <p class="text-base font-semibold text-slate-500 uppercase tracking-wide">Total Boxes</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalBoxes }}</h3>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Form & Table -->
        <div class="grid grid-cols-3 gap-6">

            <!-- Left: Quick Entry Form (1/3 Width) -->
            <div class="col-span-1 bg-white p-10 rounded-3xl border border-purple-100 shadow-sm min-h-[500px]">
                <h2 class="text-3xl font-bold text-slate-800 mb-8">Add New Entry</h2>

                <!-- Entry Form -->
                <form action="{{ url('/store-entry') }}" method="POST" id="quickEntryForm">
                    @csrf

                    <!-- Player Name Input -->
                    <div class="mb-6">
                        <label class="block text-lg font-semibold text-slate-700 mb-2">Player Name</label>
                        <input type="text" name="player_name" required autocomplete="off"
                            class="w-full px-5 py-4 text-xl bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400"
                            placeholder="Enter player name">
                    </div>

                    <!-- Dynamic Box Entries Container -->
                    <div id="box-entries-container">
                        <!-- First Entry Row (Cannot be removed) -->
                        <div class="flex gap-4 mb-4 items-end">
                            <div class="flex-1">
                                <label class="block text-lg font-semibold text-slate-700 mb-2">Box No.</label>
                                <!-- Note the array bracket [] in name attribute -->
                                <input type="number" name="box_number[]" required min="1" max="100"
                                    class="w-full px-5 py-4 text-2xl font-bold text-center bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-300"
                                    placeholder="1-100">
                            </div>

                            <div class="flex-1">
                                <label class="block text-lg font-semibold text-slate-700 mb-2">Quantity</label>
                                <!-- Note the array bracket [] in name attribute -->
                                <input type="number" name="quantity[]" required min="1" value="1"
                                    class="w-full px-5 py-4 text-2xl font-bold text-center bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-300">
                            </div>
                            <!-- Empty div to keep alignment with remove buttons of dynamic rows -->
                            <div class="w-14 hidden lg:block"></div>
                        </div>
                    </div>

                    <!-- Add Another Box Button -->
                    <button type="button" id="add-box-btn"
                        class="w-full py-4 mb-8 border-2 border-dashed border-purple-300 text-purple-600 font-bold text-lg rounded-2xl hover:bg-purple-50 transition-colors flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Another Box
                    </button>

                    <!-- Payment Status -->
                    <div class="mb-8">
                        <label class="block text-lg font-semibold text-slate-700 mb-3">Payment Status</label>
                        <div class="grid grid-cols-3 gap-4">

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_status" value="Paid" class="peer sr-only" checked>
                                <div
                                    class="w-full py-4 text-center text-xl font-bold rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-500 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all hover:bg-slate-100">
                                    Paid
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_status" value="Pending" class="peer sr-only">
                                <div
                                    class="w-full py-4 text-center text-xl font-bold rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-500 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 transition-all hover:bg-slate-100">
                                    Pending
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_status" value="Not Paid" class="peer sr-only">
                                <div
                                    class="w-full py-4 text-center text-xl font-bold rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-500 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-700 transition-all hover:bg-slate-100">
                                    Not Paid
                                </div>
                            </label>

                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold text-2xl py-5 rounded-2xl shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-4 focus:ring-purple-300">
                        Save Entry
                    </button>

                </form>
            </div>
            <!-- Right: Today's Transactions Table (2/3 Width) -->
            <div class="col-span-2 bg-white p-8 rounded-2xl border border-purple-100 shadow-sm min-h-[500px]">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Today's Transactions</h2>
                <div class="flex flex-col gap-4 text-center text-slate-400 font-medium">
                    <p></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Unified Script for Dynamic Rows, Validation & Alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Global Config variables
            const MAX_SHEETS_PER_BOX = 20;
            const MAX_BOX_NUMBER = 100;

            // 1. Toast Alert Auto-Hide Logic
            const toastSuccess = document.getElementById('toast-alert');
            const toastError = document.getElementById('toast-alert-error');

            if (toastSuccess) {
                setTimeout(() => {
                    toastSuccess.style.opacity = '0';
                    toastSuccess.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toastSuccess.remove(), 500);
                }, 3000); // Hides after 3 seconds
            }

            if (toastError) {
                setTimeout(() => {
                    toastError.style.opacity = '0';
                    toastError.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toastError.remove(), 500);
                }, 5000); // Hides after 5 seconds
            }

            // 2. Input Validation Logic
            function applyValidation(inputElement, type) {
                // Prevent typing letters and symbols
                inputElement.addEventListener('keydown', function(e) {
                    if (['e', 'E', '+', '-', '.'].includes(e.key)) {
                        e.preventDefault();
                    }
                });

                // Enforce limits and numeric only
                inputElement.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');

                    if (this.value !== "") {
                        let val = parseInt(this.value);

                        if (type === 'box') {
                            if (val > MAX_BOX_NUMBER) this.value = MAX_BOX_NUMBER;
                            if (val < 1) this.value = '';
                        } else if (type === 'qty') {
                            if (val > MAX_SHEETS_PER_BOX) this.value = MAX_SHEETS_PER_BOX;
                            if (val < 1) this.value = '';
                        }
                    }
                });
            }

            // Apply validation to existing inputs on page load
            document.querySelectorAll('input[name="box_number[]"]').forEach(el => applyValidation(el, 'box'));
            document.querySelectorAll('input[name="quantity[]"]').forEach(el => applyValidation(el, 'qty'));

            // 3. Dynamic Rows Logic (Only ONE event listener now)
            const container = document.getElementById('box-entries-container');
            const addBtn = document.getElementById('add-box-btn');

            addBtn.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'flex gap-4 mb-4 items-center entry-row';

                row.innerHTML = `
                    <div class="flex-1">
                        <input type="number" name="box_number[]" required min="1" max="${MAX_BOX_NUMBER}"
                            class="dynamic-box w-full px-5 py-4 text-2xl font-bold text-center bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-300"
                            placeholder="1-${MAX_BOX_NUMBER}">
                    </div>
                    <div class="flex-1">
                        <input type="number" name="quantity[]" required min="1" max="${MAX_SHEETS_PER_BOX}" value="1"
                            class="dynamic-qty w-full px-5 py-4 text-2xl font-bold text-center bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-300">
                    </div>
                    <button type="button" class="remove-btn w-14 h-16 bg-rose-50 text-rose-500 border border-rose-200 rounded-2xl flex items-center justify-center hover:bg-rose-100 transition-colors shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;

                container.appendChild(row);

                // Apply strict validation to the newly created inputs
                applyValidation(row.querySelector('.dynamic-box'), 'box');
                applyValidation(row.querySelector('.dynamic-qty'), 'qty');

                // Remove row functionality
                const removeBtn = row.querySelector('.remove-btn');
                removeBtn.addEventListener('click', function() {
                    row.remove();
                });
            });
        });
    </script>
@endsection
