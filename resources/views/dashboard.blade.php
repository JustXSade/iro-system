@extends('layouts.app')

@section('content')
    <!-- Success & Error Alerts -->
    @if (session('success'))
        <div id="toast-alert"
            class="fixed top-8 right-8 bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 z-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-lg font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div id="toast-alert-error"
            class="fixed top-8 right-8 bg-rose-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 z-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="text-lg font-semibold">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Live Board Section -->
    <div id="live-board-section"
        class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm mt-6 mb-6 transition-all duration-300">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <h2 class="text-3xl font-bold text-slate-800">Live Board Status 
                    <span class="text-xl text-slate-500 font-medium">(00-99)</span>
                </h2>
                <!-- Full Screen Toggle Button -->
                <button type="button" id="fullscreen-btn" class="bg-purple-100 text-purple-700 hover:bg-purple-200 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                    </svg>
                    Full Screen
                </button>
            </div>
            <!-- Status Indicators -->
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

        <!-- 10x10 Grid (00 to 99) -->
        <div class="grid grid-cols-10 gap-3">
            @for ($i = 0; $i <= 99; $i++)
                @php
                    $sold = $boxStats[$i] ?? 0;
                    $available = 20 - $sold;
                    // 0 අගයන් "00" ලෙසද, 1 අගය "01" ලෙසද බිංදුවක් සමඟ පෙන්වීමට:
                    $boxLabel = str_pad($i, 2, '0', STR_PAD_LEFT);

                    // Availability අනුව පාට තීරණය කිරීම
                    $bgClass = 'bg-green-50 border-green-200 hover:bg-green-100';
                    $textClass = 'text-green-700';
                    $badgeClass = 'bg-green-200/50 text-green-700';
                    $cursorClass = 'cursor-pointer';

                    if ($available == 0) {
                        $bgClass = 'bg-slate-50 border-slate-200 opacity-70';
                        $textClass = 'text-slate-400';
                        $badgeClass = 'bg-slate-200/50 text-slate-500';
                        $cursorClass = 'cursor-not-allowed';
                    } elseif ($available <= 5) {
                        // ඉතිරිව ඇත්තේ 5ක් හෝ ඊට අඩුවෙන් නම් (Ending Soon)
                        $bgClass = 'bg-amber-50 border-amber-200 hover:bg-amber-100';
                        $textClass = 'text-amber-600';
                        $badgeClass = 'bg-amber-200/50 text-amber-700';
                    }
                @endphp

                <button {{ $available == 0 ? 'disabled' : '' }}
                    class="{{ $bgClass }} border rounded-xl py-3 px-2 flex flex-col items-center justify-center transition-colors shadow-sm {{ $cursorClass }}">
                    <span class="text-3xl font-bold {{ $textClass }}">{{ $boxLabel }}</span>
                    <span class="text-sm font-bold {{ $badgeClass }} px-3 py-1 rounded-full mt-1.5">
                        {{ $available == 0 ? 'Sold Out' : $available . ' left' }}
                    </span>
                </button>
            @endfor
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
                <h2 class="text-3xl font-bold text-slate-800 mb-8 bg-green-100 px-6 py-4 rounded-2xl">Add New Entry</h2>

                <!-- Entry Form -->
                <form action="{{ url('/store-entry') }}" method="POST" id="quickEntryForm">
                    @csrf

                    <!-- Player Name Input with Autocomplete -->
                    <div class="mb-6 relative">
                        <label class="block text-lg font-semibold text-slate-700 mb-2">Player Name</label>
                        <input type="text" name="player_name" id="player_name" required autocomplete="off"
                            class="w-full px-5 py-4 text-xl bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400"
                            placeholder="Enter player name">

                        <!-- Suggestions Dropdown Box -->
                        <ul id="player-suggestions"
                            class="absolute z-10 w-full bg-white border border-purple-100 rounded-xl shadow-lg mt-2 hidden max-h-60 overflow-y-auto">
                            <!-- JavaScript වලින් මෙතනට නම් ටික එනවා -->
                        </ul>
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

                    <!-- Payment Section (Total Bill & Cash Paid Group) -->
                    <div class="mb-8 bg-slate-50 p-6 rounded-3xl border border-slate-200 shadow-inner">

                        <!-- Total Bill Display -->
                        <div class="flex justify-between items-center mb-6 border-b border-slate-200 pb-5">
                            <span class="text-xl font-bold text-slate-700">Total Bill Amount:</span>
                            <span class="text-4xl font-extrabold text-purple-700" id="total-bill-display">Rs. 1,000</span>
                        </div>

                        <!-- Cash Paid Now Input -->
                        <div>
                            <label class="block text-lg font-semibold text-slate-700 mb-3">Cash Paid Now (Rs.)</label>
                            <div class="relative">
                                <span
                                    class="absolute left-5 top-1/2 -translate-y-1/2 text-xl font-bold text-slate-400">Rs.</span>
                                <input type="number" name="cash_paid" min="0" value="0" required
                                    onfocus="if(this.value=='0') this.value='';"
                                    onblur="if(this.value=='') this.value='0';" onwheel="this.blur();"
                                    class="w-full pl-14 pr-5 py-4 text-2xl font-bold text-slate-800 bg-white border border-emerald-300 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 transition-all placeholder:text-emerald-300">
                            </div>
                            <p class="text-sm text-slate-500 mt-3 font-medium flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                සම්පූර්ණයෙන්ම ණයට සෙල්ලම් කරන්නේ නම් 0 ලෙස තබන්න.
                            </p>
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
            <div
                class="col-span-2 bg-white p-10 rounded-3xl border border-purple-100 shadow-sm min-h-[500px] flex flex-col">
                <h2 class="text-3xl font-bold text-slate-800 mb-8 bg-green-100 px-6 py-4 rounded-2xl">Today's Transactions
                </h2>

                <!-- Table Container with Scrollbar -->
                <div class="flex-1 overflow-y-auto pr-2" style="max-height: 480px;">
                    <table class="w-full text-left border-collapse">
                        <!-- Table Header -->
                        <thead class="sticky top-0 bg-white z-10">
                            <tr class="border-b-2 border-slate-100 text-slate-500 text-lg">
                                <th class="py-4 font-semibold w-24">Time</th>
                                <th class="py-4 font-semibold w-40">Player Name</th>
                                <th class="py-4 font-semibold">Boxes</th>
                                <th class="py-4 font-semibold text-right ">Amount</th>
                                <th class="py-4 font-semibold text-center ">Status</th>
                                <th class="py-4 font-semibold text-right ">Due Amount</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="text-lg text-slate-700">
                            @forelse($transactions as $transaction)
                                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                    <!-- Time -->
                                    <td class="py-5 text-sm text-slate-400 font-medium">
                                        {{ $transaction->created_at->format('h:i A') }}
                                    </td>

                                    <!-- Player Name -->
                                    <td class="py-5 font-bold text-slate-800">
                                        {{ $transaction->player->name }}
                                    </td>

                                    <!-- Combined Boxes -->
                                    <td class="py-5 font-semibold text-purple-700">
                                        {{ $transaction->combined_boxes }}
                                    </td>

                                    <!-- Total Amount -->
                                    <td class="py-5 font-bold text-right text-slate-800">
                                        Rs. {{ number_format($transaction->total_amount, 2) }}
                                    </td>

                                    <!-- Status Badges -->
                                    <td class="py-5 text-center">
                                        @if ($transaction->player->balance == 0)
                                            <span
                                                class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-xl text-xs font-bold border border-emerald-200">Cleared</span>
                                        @elseif($transaction->player->balance < 0)
                                            <span
                                                class="bg-rose-100 text-rose-700 px-3 py-1.5 rounded-xl text-xs font-bold border border-rose-200">Pending</span>
                                        @else
                                            <span
                                                class="bg-amber-100 text-amber-700 px-3 py-1.5 rounded-xl text-xs font-bold border border-amber-200">Advance</span>
                                        @endif
                                    </td>

                                    <!-- Due Amount -->
                                    <td class="py-5 font-bold text-right whitespace-nowrap">
                                        @if ($transaction->player->balance < 0)
                                            <span class="text-rose-600">Rs.
                                                {{ number_format(abs($transaction->player->balance), 2) }}</span>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center text-slate-400">
                                        <span class="text-lg font-medium">No transactions yet for today.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
            const UNIT_PRICE = 100; // Box එකක මිල (මේක Backend එකට සමාන විය යුතුයි)

            // 1. Toast Alert Auto-Hide Logic
            const toastSuccess = document.getElementById('toast-alert');
            const toastError = document.getElementById('toast-alert-error');

            if (toastSuccess) {
                setTimeout(() => {
                    toastSuccess.style.opacity = '0';
                    toastSuccess.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toastSuccess.remove(), 500);
                }, 3000);
            }

            if (toastError) {
                setTimeout(() => {
                    toastError.style.opacity = '0';
                    toastError.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toastError.remove(), 500);
                }, 5000);
            }

            // --- NEW: Calculate Total Bill Function ---
            function calculateTotalBill() {
                let totalQuantity = 0;
                // Get all quantity inputs
                const qtyInputs = document.querySelectorAll('input[name="quantity[]"]');

                qtyInputs.forEach(input => {
                    let val = parseInt(input.value) || 0;
                    totalQuantity += val;
                });

                const totalAmount = totalQuantity * UNIT_PRICE;
                // Update the display with commas (e.g. 5,000)
                document.getElementById('total-bill-display').innerText = 'Rs. ' + totalAmount.toLocaleString(
                    'en-US');
            }

            // 2. Input Validation Logic
            function applyValidation(inputElement, type) {
                inputElement.addEventListener('keydown', function(e) {
                    if (['e', 'E', '+', '-', '.'].includes(e.key)) {
                        e.preventDefault();
                    }
                });

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

                            // Update total whenever quantity changes
                            calculateTotalBill();
                        }
                    } else if (type === 'qty') {
                        calculateTotalBill(); // Update even if empty
                    }
                });
            }

            // Apply validation to existing inputs on page load
            document.querySelectorAll('input[name="box_number[]"]').forEach(el => applyValidation(el, 'box'));
            document.querySelectorAll('input[name="quantity[]"]').forEach(el => applyValidation(el, 'qty'));

            // Run calculation once on page load
            calculateTotalBill();

            // 3. Dynamic Rows Logic
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

                // Update total because a new row with default qty=1 was added
                calculateTotalBill();

                // Remove row functionality
                const removeBtn = row.querySelector('.remove-btn');
                removeBtn.addEventListener('click', function() {
                    row.remove();
                    // Update total when a row is removed
                    calculateTotalBill();
                });
            });

            // --- Player Name Autocomplete Logic ---
            const playerNameInput = document.getElementById('player_name');
            const suggestionsBox = document.getElementById('player-suggestions');

            playerNameInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 1) {
                    suggestionsBox.classList.add('hidden');
                    suggestionsBox.innerHTML = '';
                    return;
                }

                // Fetch matched players from backend
                fetch(`/players/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(players => {
                        suggestionsBox.innerHTML = '';

                        if (players.length > 0) {
                            suggestionsBox.classList.remove('hidden');

                            players.forEach(player => {
                                const li = document.createElement('li');
                                li.className =
                                    'px-5 py-3 hover:bg-purple-50 cursor-pointer border-b border-slate-50 last:border-0 flex justify-between items-center transition-colors';

                                // Check if player has debt or advance
                                let balanceHtml = '';
                                if (player.balance < 0) {
                                    balanceHtml =
                                        `<span class="bg-rose-100 text-rose-700 px-2 py-1 rounded-lg text-xs font-bold border border-rose-200">Naya: Rs. ${Math.abs(player.balance).toLocaleString()}</span>`;
                                } else if (player.balance > 0) {
                                    balanceHtml =
                                        `<span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg text-xs font-bold border border-emerald-200">Adv: Rs. ${player.balance.toLocaleString()}</span>`;
                                }

                                li.innerHTML =
                                    `<span class="text-lg font-bold text-slate-700">${player.name}</span> ${balanceHtml}`;

                                // Click to select player
                                li.addEventListener('click', () => {
                                    playerNameInput.value = player.name;
                                    suggestionsBox.classList.add('hidden');
                                });

                                suggestionsBox.appendChild(li);
                            });
                        } else {
                            suggestionsBox.classList.add('hidden');
                        }
                    })
                    .catch(error => console.error('Error fetching players:', error));
            });

            // Hide dropdown if clicked outside
            document.addEventListener('click', function(e) {
                if (!playerNameInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.classList.add('hidden');
                }
            });

        });

        // --- Full Screen Live Board Logic ---
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const liveBoardSection = document.getElementById('live-board-section');

    fullscreenBtn.addEventListener('click', function() {
        // Tailwind classes toggle කිරීම මගින් Full screen කිරීම
        liveBoardSection.classList.toggle('fixed');
        liveBoardSection.classList.toggle('inset-0');
        liveBoardSection.classList.toggle('z-[100]');
        liveBoardSection.classList.toggle('w-full');
        liveBoardSection.classList.toggle('h-screen');
        liveBoardSection.classList.toggle('overflow-y-auto');
        liveBoardSection.classList.toggle('rounded-none');
        
        // Full screen අවස්ථාවේදී margin සහ border අයින් කිරීම
        liveBoardSection.classList.toggle('mt-6');
        liveBoardSection.classList.toggle('mb-6');
        liveBoardSection.classList.toggle('border');
        
        // Button එකේ Text එක වෙනස් කිරීම
        if (liveBoardSection.classList.contains('fixed')) {
            fullscreenBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25" />
                </svg>
                Exit Full Screen
            `;
            fullscreenBtn.classList.replace('bg-purple-100', 'bg-rose-100');
            fullscreenBtn.classList.replace('text-purple-700', 'text-rose-700');
        } else {
            fullscreenBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                </svg>
                Full Screen
            `;
            fullscreenBtn.classList.replace('bg-rose-100', 'bg-purple-100');
            fullscreenBtn.classList.replace('text-rose-700', 'text-purple-700');
        }
    });


    </script>
@endsection
