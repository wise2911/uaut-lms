<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments | UAUT LMS</title>
    <link rel="shortcut icon" href="{{ url('img/uaut-logo.jpg') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#1f2937',
                        accent: '#10b981',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased flex">
    <aside id="sidebar" class="bg-secondary text-white w-64 min-h-screen p-4 fixed top-0 left-0 transition-transform duration-300 transform lg:translate-x-0 -translate-x-full z-20">
        <div class="flex items-center mb-8">
            <img src="{{ url('img/uaut-logo.jpg') }}" alt="UAUT LMS" class="h-8 w-8 mr-3">
            <h1 class="text-xl font-bold">UAUT LMS Admin</h1>
        </div>
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.videos.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.videos.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-video mr-3"></i>
                        <span>Manage Videos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.videos.create') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.videos.create') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-plus mr-3"></i>
                        <span>Add Course</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.users') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        <span>Manage Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ratings') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.ratings') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-star mr-3"></i>
                        <span>View Ratings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.payments') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.payments') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-money-bill mr-3"></i>
                        <span>View Payments</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center p-2 w-full text-left rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="flex-1 p-6 lg:ml-64 transition-all duration-300">
        <button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-30 bg-primary text-white p-2 rounded-full">
            <i class="fas fa-bars"></i>
        </button>

        <header class="bg-white shadow-sm rounded-lg p-4 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Manage Payments</h1>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($payments->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-money-bill text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">No payments recorded yet</h3>
                </div>
            @else
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-center">
                    <form method="GET" action="{{ route('admin.payments') }}" class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <input type="text" id="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by user, course, or PayPal ID..." class="w-full sm:w-64 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        <select name="department" class="w-full sm:w-48 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}" {{ $department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </form>
                    <div class="mt-4 sm:mt-0 flex space-x-2">
                        <button id="sort-amount" class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">Sort by Amount</button>
                        <button id="sort-date" class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">Sort by Date</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-medium">User</th>
                                <th class="px-6 py-3 text-left font-medium">Course</th>
                                <th class="px-6 py-3 text-left font-medium">Amount</th>
                                <th class="px-6 py-3 text-left font-medium">PayPal Order ID</th>
                                <th class="px-6 py-3 text-left font-medium">PayPal Transaction ID</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody id="payments-table" class="divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">{{ $payment->user->full_name }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $payment->course->title }}</td>
                                    <td class="px-6 py-4 text-gray-700">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $payment->paypal_order_id ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $payment->paypal_transaction_id ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'COMPLETED' ? 'bg-green-100 text-green-800' : ($payment->status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">
                    {{ $payments->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        const searchInput = document.getElementById('search');
        const sortAmount = document.getElementById('sort-amount');
        const sortDate = document.getElementById('sort-date');
        const tableBody = document.getElementById('payments-table');
        let payments = @json($payments);

        function renderTable(data) {
            tableBody.innerHTML = '';
            data.forEach(payment => {
                const statusClass = payment.status === 'Complete' ? 'bg-green-100 text-green-800' : 
                                   payment.status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800';
                tableBody.innerHTML += `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-700">${payment.user.full_name}</td>
                        <td class="px-6 py-4 text-gray-700">${payment.course.title}</td>
                        <td class="px-6 py-4 text-gray-700">$${parseFloat(payment.amount).toFixed(2)}</td>
                        <td class="px-6 py-4 text-gray-700">${payment.paypal_order_id || 'N/A'}</td>
                        <td class="px-6 py-4 text-gray-700">${payment.paypal_transaction_id || 'N/A'}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                ${payment.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">${new Date(payment.created_at).toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                    </tr>
                `;
            });
        }

        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            const filtered = payments.filter(payment =>
                payment.user.full_name.toLowerCase().includes(term) ||
                payment.course.title.toLowerCase().includes(term) ||
                (payment.paypal_order_id && payment.paypal_order_id.toLowerCase().includes(term)) ||
                (payment.paypal_transaction_id && payment.paypal_transaction_id.toLowerCase().includes(term))
            );
            renderTable(filtered);
        });

        sortAmount.addEventListener('click', () => {
            payments.sort((a, b) => parseFloat(a.amount) - parseFloat(b.amount));
            renderTable(payments);
        });

        sortDate.addEventListener('click', () => {
            payments.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            renderTable(payments);
        });
    </script>
</body>
</html>