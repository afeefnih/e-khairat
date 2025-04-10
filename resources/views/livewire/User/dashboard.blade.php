<div x-data="{ open: false, payment: {} }" class="min-w-screen rounded-2xl bg-gray-50 dark:bg-gray-900">

    <!-- Main Content -->
    <div class="py-4 px-4 sm:py-6 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Success Alert (Condensed) -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4">
                    <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-600 p-3 rounded-lg flex items-center justify-between">
                        <p class="text-green-700 dark:text-green-100 text-sm">{{ session('success') }}</p>
                        <button @click="show = false" class="text-green-500 hover:text-green-700 dark:hover:text-green-300">
                        </button>
                    </div>
                </div>
            @endif

            <!-- User Profile Card (Compact) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-4">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 dark:text-blue-300 text-lg md:text-xl font-medium">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </span>
                    </div>
                    <div class="text-center md:text-left w-full">
                        <h2 class="text-lg md:text-xl font-medium text-gray-900 dark:text-white">
                            {{ auth()->user()->name }}
                        </h2>
                        <div class="flex justify-center md:justify-start flex-wrap gap-2 mt-2">
                            <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-xs font-medium rounded-full">
                                {{ auth()->user()->ic_number }}
                            </span>
                            <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-full">
                                Member since {{ auth()->user()->created_at->format('F j, Y') }}
                            </span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm text-center md:text-left">
                            {{ auth()->user()->address }}
                        </p>
                    </div>
                </div>
            </div>

           <!-- Summary Cards (Responsive Grid) -->
           <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 transition-all hover:shadow-md border border-gray-100 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Jumlah Bayaran (RM)</h3>
                <p class="text-gray-900 dark:text-white text-2xl md:text-3xl font-semibold mt-2">
                    {{ number_format($totalPayments, 2) }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 transition-all hover:shadow-md border border-gray-100 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Jumlah Tunggakan (RM)</h3>
                <p class="text-gray-900 dark:text-white text-2xl md:text-3xl font-semibold mt-2">
                    {{ number_format($outstandingAmount, 2) }}
                </p>
                @if(isset($outstandingCategories) && $outstandingCategories->count() > 0)
                    <div class="mt-3" x-data="{
                        scrollToOutstanding() {
                            $el.blur();
                            document.getElementById('outstanding-payments').scrollIntoView({ behavior: 'smooth', block: 'start' });

                            // Toggle classes for highlight effect using only Tailwind classes
                            setTimeout(() => {
                                document.getElementById('outstanding-payments').classList.add('ring-4', 'ring-indigo-300', 'dark:ring-indigo-700');
                                setTimeout(() => {
                                    document.getElementById('outstanding-payments').classList.remove('ring-4', 'ring-indigo-300', 'dark:ring-indigo-700');
                                }, 1500);
                            }, 500);
                        }
                    }">
                        <button
                            type="button"
                            @click="scrollToOutstanding"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            <span>Lihat Tunggakan</span>
                            <svg class="w-4 h-4 ml-2 transition-transform duration-200" :class="{ 'transform translate-y-1': $hover }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>

          <!-- Dashboard View: Outstanding Payments Section -->
@if(isset($outstandingCategories) && $outstandingCategories->count() > 0)
<div id="outstanding-payments" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-100 dark:border-gray-700 mt-4">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Bayaran Tertunggak</h3>
    <div class="overflow-x-auto rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                    <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah (RM)</th>
                    <th scope="col" class="py-3 px-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tindakan</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($outstandingCategories as $category)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ $category->category_name }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ $category->category_description ?? 'No description available' }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ number_format($category->amount, 2) }}
                        </td>
                        <td class="py-3 px-4 text-sm text-right">
                            <a href="{{ route('payments.process', $category->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-400 dark:hover:bg-indigo-900/80 rounded-lg text-xs font-medium transition-colors">
                                Bayar Sekarang
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

            <!-- Recent Payments Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Payments</h3>
                <div class="overflow-x-auto rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider rounded-tl-xl">Yuran</th>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah (RM)</th>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider rounded-tr-xl">Resit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $payment->payment_category->category_name ?? 'N/A' }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                            {{ $payment->status_id == 1
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                            {{ $payment->status_id == 1 ? 'Successful' : 'Failed' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            @if($payment->billcode)
                                                <a href="https://dev.toyyibpay.com/{{ $payment->billcode }}"
                                                   target="_blank"
                                                   class="inline-flex items-center justify-center px-2 py-1
                                                          bg-blue-50 hover:bg-blue-100 text-blue-700
                                                          dark:bg-blue-900/50 dark:text-blue-400
                                                          dark:hover:bg-blue-900/80 rounded-lg
                                                          text-xs font-medium transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    {{ substr($payment->billcode, 0, 8) }}...
                                                </a>
                                            @endif
                                            <button
                                                @click="open = true; payment = $payment"
                                                class="inline-flex items-center justify-center px-2 py-1
                                                       bg-green-50 hover:bg-green-100 text-green-700
                                                       dark:bg-green-900/50 dark:text-green-400
                                                       dark:hover:bg-green-900/80 rounded-lg
                                                       text-xs font-medium transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Receipt
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                        No payment records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Receipt Modal -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="open = false"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false"
                 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button @click="open = false" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Payment Receipt
                        </h3>
                    </div>

                    <div class="mt-5">
                        <div class="border-t border-b border-gray-200 dark:border-gray-700 py-4">
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Receipt Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">#{{ $payment->id }}</dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->paid_at }}</dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">RM {{ $payment->amount }}</dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->paymentCategory->category_name ?? 'FPX' }}</dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status_id == 1 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                            {{ $payment->status_id == 1 ? 'Completed' : 'Pending' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Customer Information -->
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Customer Information</h4>
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ auth()->user()->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ auth()->user()->address ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 sm:flex sm:flex-row-reverse">
                    <a href="{{ route('register', ['payment_id' => $payment->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Receipt
                    </a>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-650 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        window.addEventListener('scroll-to-outstanding', event => {
            document.getElementById('outstanding-payments').scrollIntoView({ behavior: 'smooth', block: 'start' });

            setTimeout(() => {
                document.getElementById('outstanding-payments').classList.add('ring-4', 'ring-indigo-300', 'dark:ring-indigo-700');
                setTimeout(() => {
                    document.getElementById('outstanding-payments').classList.remove('ring-4', 'ring-indigo-300', 'dark:ring-indigo-700');
                }, 1500);
            }, 500);
        });
    });
</script>
