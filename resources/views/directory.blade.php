@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div id="toast-alert" class="fixed top-8 right-8 bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 z-[200]">
            <span class="text-lg font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div id="toast-alert-error" class="fixed top-8 right-8 bg-rose-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 z-[200]">
            <span class="text-lg font-semibold">{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="max-w-7xl mx-auto">
        <!-- Header & Live Search -->
        <div class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-slate-800">Players & Settlements</h2>
            
            <div class="relative w-96">
                <input type="text" id="player-search-input" placeholder="Search player name..." autocomplete="off"
                    class="w-full pl-5 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all font-medium text-slate-700">
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Players Table -->
        <div class="bg-white p-8 rounded-3xl border border-purple-100 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-slate-100 text-slate-500 text-lg">
                        <th class="py-4 font-semibold w-1/2">Player Name</th>
                        <th class="py-4 font-semibold cursor-pointer hover:text-purple-600 transition-colors" onclick="sortByStatus()">
                            <div class="flex items-center gap-2">
                                Status
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                </svg>
                            </div>
                        </th>
                        <th class="py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-lg text-slate-700" id="players-table-body">
                    @forelse ($players as $player)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors player-row">
                            <td class="py-5 font-bold text-slate-800 player-name">{{ $player->name }}</td>
                            <td class="py-5">
                                @if ($player->balance < 0)
                                    <span class="bg-rose-100 text-rose-700 px-4 py-1.5 rounded-xl text-sm font-bold border border-rose-200">
                                        Due: Rs. {{ number_format(abs($player->balance), 2) }}
                                    </span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-xl text-sm font-bold border border-emerald-200">
                                        Cleared
                                    </span>
                                @endif
                            </td>
                            <td class="py-5 text-right">
                                <button onclick="openPlayerModal({{ $player->id }})" class="bg-purple-100 text-purple-700 hover:bg-purple-600 hover:text-white px-5 py-2 rounded-xl text-sm font-bold transition-colors border border-purple-200 hover:border-purple-600 shadow-sm">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-row">
                            <td colspan="3" class="py-16 text-center text-slate-400 font-medium text-lg">
                                No players found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Player Details Modal (Simplified for Debt Settlement Only) -->
    <div id="player-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closePlayerModal()"></div>
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800" id="modal-player-name">Loading...</h3>
                    <p class="text-sm font-semibold mt-1" id="modal-player-status"></p>
                </div>
                <button onclick="closePlayerModal()" class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center hover:bg-rose-100 hover:text-rose-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-8 bg-white">
                
                <!-- Debt Settlement Section -->
                <div id="settlement-section" class="bg-rose-50 border border-rose-200 p-6 rounded-2xl hidden">
                    <h4 class="text-lg font-bold text-rose-800 mb-4">Settle Pending Debt</h4>
                    <form action="{{ url('/settle-debt') }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        <input type="hidden" name="player_id" id="settle-player-id">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-rose-700 mb-2">Amount to Pay (Rs.)</label>
                            <input type="number" name="amount" id="settle-amount-input" required min="1" 
                                class="w-full px-5 py-3 text-xl font-bold text-slate-800 bg-white border border-rose-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-rose-100 focus:border-rose-500 transition-all">
                        </div>
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-lg px-8 py-3 rounded-xl shadow-md transition-colors h-[54px]">
                            Pay Now
                        </button>
                    </form>
                </div>

                <!-- Cleared Account Message -->
                <div id="no-debt-message" class="text-center py-8 hidden">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">No Pending Debts</h4>
                    <p class="text-slate-500 mt-2">This player's account is completely cleared.</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastSuccess = document.getElementById('toast-alert');
            const toastError = document.getElementById('toast-alert-error');
            if (toastSuccess) {
                setTimeout(() => { toastSuccess.style.opacity = '0'; setTimeout(() => toastSuccess.remove(), 500); }, 3000);
            }
            if (toastError) {
                setTimeout(() => { toastError.style.opacity = '0'; setTimeout(() => toastError.remove(), 500); }, 5000);
            }

            // Live Search Logic (Frontend Filtering)
            const searchInput = document.getElementById('player-search-input');
            const playerRows = document.querySelectorAll('.player-row');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                playerRows.forEach(row => {
                    const name = row.querySelector('.player-name').innerText.toLowerCase();
                    if (name.includes(query)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                let emptyRow = document.getElementById('empty-row');
                if (visibleCount === 0) {
                    if (!emptyRow) {
                        const tbody = document.getElementById('players-table-body');
                        tbody.insertAdjacentHTML('beforeend', '<tr id="empty-row"><td colspan="3" class="py-16 text-center text-slate-400 font-medium text-lg">No players found matching your search.</td></tr>');
                    } else {
                        emptyRow.style.display = '';
                    }
                } else if (emptyRow) {
                    emptyRow.style.display = 'none';
                }
            });
        });

        const modal = document.getElementById('player-modal');
        
        function openPlayerModal(playerId) {
            modal.classList.remove('hidden');
            
            document.getElementById('modal-player-name').innerText = 'Loading details...';
            document.getElementById('modal-player-status').innerHTML = '';
            document.getElementById('settlement-section').classList.add('hidden');
            document.getElementById('no-debt-message').classList.add('hidden');

            fetch(`/api/players/${playerId}/details`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modal-player-name').innerText = data.name;
                    
                    const statusEl = document.getElementById('modal-player-status');
                    const settlementSec = document.getElementById('settlement-section');
                    const noDebtMsg = document.getElementById('no-debt-message');
                    const settleInput = document.getElementById('settle-amount-input');
                    const settlePlayerId = document.getElementById('settle-player-id');

                    if (data.balance < 0) {
                        let due = Math.abs(data.balance);
                        let squaresCount = Math.floor(due / 100); 
                        
                        statusEl.innerHTML = `
                            <div class="flex items-center gap-5 mt-2">
                                <span class="text-rose-600 text-lg font-bold">Total Due: Rs. ${due.toLocaleString()}</span>
                                <span class="bg-rose-100 text-rose-800 px-3 py-1 rounded-lg text-sm font-extrabold border border-rose-300 shadow-sm">
                                    ${squaresCount} Squares Pending
                                </span>
                            </div>
                        `;
                        settlementSec.classList.remove('hidden');
                        settleInput.max = due;
                        settleInput.value = due; 
                        settlePlayerId.value = data.id;
                    } else {
                        statusEl.innerHTML = `<span class="text-emerald-600">Account Cleared</span>`;
                        noDebtMsg.classList.remove('hidden');
                    }
                })
                .catch(err => console.error(err));
        }

        function closePlayerModal() {
            modal.classList.add('hidden');
        }

        let isDueFirst = false;

        function sortByStatus() {
            const tbody = document.getElementById('players-table-body');
            const rows = Array.from(tbody.querySelectorAll('.player-row'));
            
            isDueFirst = !isDueFirst; 

            rows.sort((a, b) => {
                const statusA = a.cells[1].innerText.toLowerCase();
                const statusB = b.cells[1].innerText.toLowerCase();
                
                const hasDueA = statusA.includes('due');
                const hasDueB = statusB.includes('due');

                if (hasDueA && !hasDueB) return isDueFirst ? -1 : 1;
                if (!hasDueA && hasDueB) return isDueFirst ? 1 : -1;
                return 0;
            });

            rows.forEach(row => tbody.appendChild(row));
        }

    </script>
@endsection