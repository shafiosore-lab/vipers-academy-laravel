@extends('layouts.staff')

@section('title', 'Finance Reports - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Financial Reports</h2>
                            <p class="mb-0">Monthly revenue analytics</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('finance.dashboard') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Report -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Monthly Revenue (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    @if(isset($monthlyData) && $monthlyData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Total Revenue</th>
                                        <th>Change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $previousTotal = null;
                                    @endphp
                                    @foreach($monthlyData as $data)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($data->month)->format('F Y') }}</td>
                                            <td>KSh {{ number_format($data->total, 2) }}</td>
                                            <td>
                                                @if($previousTotal !== null)
                                                    @php
                                                        $change = $data->total - $previousTotal;
                                                        $percentChange = $previousTotal > 0 ? ($change / $previousTotal) * 100 : 0;
                                                    @endphp
                                                    <span class="text-{{ $change >= 0 ? 'success' : 'danger' }}">
                                                        <i class="fas fa-{{ $change >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                                        {{ number_format(abs($percentChange), 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $previousTotal = $data->total;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th>Total</th>
                                        <th>KSh {{ number_format($monthlyData->sum('total'), 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
