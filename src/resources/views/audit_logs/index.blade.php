@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Staff Changing History</h4>
                <h6>Audit Logs</h6>
            </div>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew" id="customer-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Staff</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Service Changing</th>
                                <th>Discount Type</th>
                                <th>Discount Changing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($auditLogs as $auditLog)
                            <tr>
                                <td>{{ $auditLog->created_at }}</td>
                                <td>{{ $auditLog->user->name ?? ''}}</td>
                                <td>{{ $auditLog->customer?->name }}</td>
                                <td>{{ $auditLog->status  }}</td>
                                <td>{{ $auditLog->services->service_name ?? ''  }}</td>
                                <td>{{ $auditLog->discount_type  }}</td>
                                <td>{{ $auditLog->discount == '' ? 0 : $auditLog->discount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
