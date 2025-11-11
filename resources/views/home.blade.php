@extends('layouts.app')

@section('content')

<!-- Dashboard Header with Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Dashboard</h1>
            <div class="dashboard-quick-actions">
                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-plus"></i> New Order
                </a>
                <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-building"></i> New Company
                </a>
                <a href="{{ route('persons.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-user"></i> New Person
                </a>
            </div>
        </div>

        <!-- Filters Panel -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}" id="dashboardFilters">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="period">Time Period</label>
                            <select name="period" id="period" class="form-control form-control-sm">
                                <option value="7" {{ $filters['period'] == 7 ? 'selected' : '' }}>7 Days</option>
                                <option value="30" {{ $filters['period'] == 30 ? 'selected' : '' }}>30 Days</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status">Order Status</label>
                            <select name="status" id="status" class="form-control form-control-sm">
                                <option value="">All Statuses</option>
                                <option value="In Progress" {{ $filters['status'] == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Finished" {{ $filters['status'] == 'Finished' ? 'selected' : '' }}>Finished</option>
                                <option value="Not Active" {{ $filters['status'] == 'Not Active' ? 'selected' : '' }}>Not Active</option>
                                <option value="Cancelled" {{ $filters['status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="payment_status">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-control form-control-sm">
                                <option value="">All Payments</option>
                                <option value="Paid" {{ $filters['payment_status'] == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Partially paid" {{ $filters['payment_status'] == 'Partially paid' ? 'selected' : '' }}>Partially Paid</option>
                                <option value="Not paid" {{ $filters['payment_status'] == 'Not paid' ? 'selected' : '' }}>Not Paid</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="user_id">User</label>
                            <select name="user_id" id="user_id" class="form-control form-control-sm">
                                <option value="">All Users</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ $filters['user_id'] == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from">Date From</label>
                            <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to">Date To</label>
                            <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-filter"></i> Apply Filters
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-stat">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Companies</h6>
                        <h3 class="mb-0">{{ number_format($statistics['companies']) }}</h3>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
                <a href="{{ route('companies.index') }}" class="card-link">View all <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-stat">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Persons</h6>
                        <h3 class="mb-0">{{ number_format($statistics['persons']) }}</h3>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                <a href="{{ route('persons.index') }}" class="card-link">View all <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-stat">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h3 class="mb-0">{{ number_format($statistics['orders']) }}</h3>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-file"></i>
                    </div>
                </div>
                <a href="{{ route('orders.index') }}" class="card-link">View all <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-stat">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Invoices</h6>
                        <h3 class="mb-0">{{ number_format($statistics['invoices']) }}</h3>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
                <a href="{{ route('invoices.index') }}" class="card-link">View all <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Financial Metrics -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-financial">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Revenue</h6>
                <h3 class="mb-0">€{{ number_format($totalRevenue, 2) }}</h3>
                <small class="text-muted">All time</small>
                @if(isset($debug) && $debug['orderServicesCount'] == 0)
                    <small class="text-danger d-block mt-1">
                        <i class="fa-solid fa-exclamation-triangle"></i> No order services found
                        @if(isset($debug['totalOrdersCount']) && $debug['totalOrdersCount'] > 0)
                            <br><span class="text-muted">({{ $debug['totalOrdersCount'] }} orders exist, but none have services attached)</span>
                        @endif
                    </small>
                @elseif(isset($debug) && $totalRevenue == 0 && $debug['orderServicesCount'] > 0)
                    <small class="text-warning d-block mt-1"><i class="fa-solid fa-info-circle"></i> {{ $debug['orderServicesCount'] }} services, but costs may be 0</small>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-financial">
            <div class="card-body">
                <h6 class="text-muted mb-1">Revenue This Period</h6>
                <h3 class="mb-0">€{{ number_format($revenueThisPeriod, 2) }}</h3>
                <small class="text-muted">{{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-financial">
            <div class="card-body">
                <h6 class="text-muted mb-1">Outstanding Revenue</h6>
                <h3 class="mb-0 text-warning">€{{ number_format($outstandingRevenue, 2) }}</h3>
                <small class="text-muted">Unpaid/Partially Paid</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-financial">
            <div class="card-body">
                <h6 class="text-muted mb-1">Paid This Period</h6>
                <h3 class="mb-0 text-success">€{{ number_format($paidThisPeriod, 2) }}</h3>
                <small class="text-muted">Payments received</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="dashboard-card card-financial">
            <div class="card-body">
                <h6 class="text-muted mb-1">Unpaid Invoices</h6>
                <h3 class="mb-0 text-danger">{{ number_format($unpaidInvoicesCount) }}</h3>
                <small class="text-muted">Awaiting payment</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-chart-line"></i> Revenue Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-chart-pie"></i> Orders by Status</h5>
            </div>
            <div class="card-body">
                <canvas id="ordersStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-chart-bar"></i> Payment Status</h5>
            </div>
            <div class="card-body">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-user"></i> My Orders by Status</h5>
            </div>
            <div class="card-body">
                <canvas id="myOrdersStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Activity Section -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-clock"></i> Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}">
                                            <i class="fa-solid fa-file"></i> {{ $order->number ?? $order->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->status == 'Finished' ? 'success' : ($order->status == 'In Progress' ? 'warning' : 'secondary') }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No recent orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-exclamation-triangle"></i> Orders Needing Attention</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Last Updated</th>
                                <th>Responsible</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notRecentlyUpdatedOrders->take(10) as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}">
                                            <i class="fa-solid fa-file"></i> {{ $order->name }}
                                        </a>
                                    </td>
                                    <td>{{ $order->updated_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        @if($order->responsible_user)
                                            <i class="fa-solid fa-user-tie"></i> {{ $order->responsible_user->name }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">All orders are up to date</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Renewals and KYC -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-calendar-alt"></i> Upcoming Renewals</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Service</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingRenewals as $order)
                                @php
                                    $expiringService = null;
                                    $expiringSoonDate = \Carbon\Carbon::now()->addDays(30);
                                    foreach ($order->orderServices as $service) {
                                        if ($service->date_to) {
                                            try {
                                                $expiryDate = null;
                                                if (is_string($service->date_to) && strpos($service->date_to, '.') !== false) {
                                                    $expiryDate = \Carbon\Carbon::createFromFormat('d.m.Y', $service->date_to);
                                                } else {
                                                    $expiryDate = \Carbon\Carbon::parse($service->date_to);
                                                }
                                                if ($expiryDate && $expiryDate->between(\Carbon\Carbon::now(), $expiringSoonDate)) {
                                                    $expiringService = $service;
                                                    break;
                                                }
                                            } catch (\Exception $e) {
                                                continue;
                                            }
                                        }
                                    }
                                @endphp
                                @if($expiringService)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}">
                                                <i class="fa-solid fa-file"></i> {{ $order->name }}
                                            </a>
                                        </td>
                                        <td>{{ $expiringService->name }}</td>
                                        <td>
                                            <span class="badge badge-warning">
                                                @php
                                                    try {
                                                        if (is_string($expiringService->date_to) && strpos($expiringService->date_to, '.') !== false) {
                                                            $date = \Carbon\Carbon::createFromFormat('d.m.Y', $expiringService->date_to);
                                                        } else {
                                                            $date = \Carbon\Carbon::parse($expiringService->date_to);
                                                        }
                                                        echo $date->format('d.m.Y');
                                                    } catch (\Exception $e) {
                                                        echo $expiringService->date_to;
                                                    }
                                                @endphp
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No upcoming renewals</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-shield-alt"></i> KYC Expiring Soon</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Entity</th>
                                <th>Type</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kycExpiringSoon as $kyc)
                                <tr>
                                    <td>
                                        @if($kyc->kycable_type == 'App\Models\Company')
                                            <a href="{{ route('companies.show', $kyc->kycable_id) }}">
                                                <i class="fa-solid fa-building"></i> {{ $kyc->kycable->name ?? 'N/A' }}
                                            </a>
                                        @else
                                            <a href="{{ route('persons.show', $kyc->kycable_id) }}">
                                                <i class="fa-solid fa-user"></i> {{ $kyc->kycable->name ?? 'N/A' }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ class_basename($kyc->kycable_type) }}</td>
                                    <td>
                                        <span class="badge badge-warning">
                                            {{ \Carbon\Carbon::parse($kyc->end_date)->format('d.m.Y') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No KYC expiring soon</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User-Specific Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-tasks"></i> My Unpaid Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Created at</th>
                                <th>Status</th>
                                <th>Payment status</th>
                                <th>Services Cost</th>
                                <th>Relation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myOrdersNotPaid as $order)
                                @php
                                    $servicesTotalCost = 0;
                                    foreach ($order->orderServices as $service) {
                                        $servicesTotalCost += (float) $service->cost;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <strong>
                                            <a href="{{ route('orders.show', $order->id) }}">
                                                <i class="fa-solid fa-file"></i> {{ $order->number ?? '' }} - {{ $order->name }}
                                            </a>
                                        </strong>
                                    </td>
                                    <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status == 'In Progress' ? 'warning' : ($order->status == 'Finished' ? 'success' : ($order->status == 'Not Active' ? 'danger' : 'secondary')) }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->payment_status == 'Paid' ? 'success' : ($order->payment_status == 'Partially paid' ? 'warning' : 'danger') }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                    <td>€{{ number_format($servicesTotalCost, 2) }}</td>
                                    <td>
                                        @if($order->company)
                                            <a href="{{ route('companies.show', $order->company->id) }}">
                                                <i class="fa-solid fa-building"></i> {{ $order->company->name }}
                                            </a>
                                        @elseif($order->person)
                                            <a href="{{ route('persons.show', $order->person->id) }}">
                                                <i class="fa-solid fa-user"></i> {{ $order->person->name }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No unpaid orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Stats -->
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">My Active Orders</h6>
                <h2 class="mb-0">{{ $myActiveOrders }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">My Revenue This Period</h6>
                <h2 class="mb-0 text-success">€{{ number_format($myRevenueThisPeriod, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">My Unpaid Orders</h6>
                <h2 class="mb-0 text-warning">{{ $myOrdersNotPaid->count() }}</h2>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueData = @json($revenueTrendData);
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(d => d.label),
                datasets: [{
                    label: 'Revenue (€)',
                    data: revenueData.map(d => d.revenue),
                    borderColor: '#DC2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Orders Status Chart
    const ordersStatusCtx = document.getElementById('ordersStatusChart');
    if (ordersStatusCtx) {
        const ordersStatusData = @json($ordersByStatus);
        new Chart(ordersStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['In Progress', 'Finished', 'Not Active', 'Cancelled'],
                datasets: [{
                    data: [
                        ordersStatusData.in_progress || 0,
                        ordersStatusData.finished || 0,
                        ordersStatusData.not_active || 0,
                        ordersStatusData.cancelled || 0
                    ],
                    backgroundColor: [
                        '#F59E0B',
                        '#10B981',
                        '#EF4444',
                        '#6B7280'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });
    }

    // Payment Status Chart
    const paymentStatusCtx = document.getElementById('paymentStatusChart');
    if (paymentStatusCtx) {
        const invoicesStatusData = @json($invoicesByStatus);
        new Chart(paymentStatusCtx, {
            type: 'bar',
            data: {
                labels: ['Paid', 'Unpaid'],
                datasets: [{
                    label: 'Invoices',
                    data: [
                        invoicesStatusData.paid || 0,
                        invoicesStatusData.unpaid || 0
                    ],
                    backgroundColor: ['#10B981', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // My Orders Status Chart
    const myOrdersStatusCtx = document.getElementById('myOrdersStatusChart');
    if (myOrdersStatusCtx) {
        const myOrdersStatusData = @json($myOrdersByStatus);
        new Chart(myOrdersStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['In Progress', 'Finished', 'Not Active'],
                datasets: [{
                    data: [
                        myOrdersStatusData.in_progress || 0,
                        myOrdersStatusData.finished || 0,
                        myOrdersStatusData.not_active || 0
                    ],
                    backgroundColor: [
                        '#F59E0B',
                        '#10B981',
                        '#EF4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });
    }
});
</script>
@endpush
