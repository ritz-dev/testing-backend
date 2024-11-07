@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Services Report</h4>
                <h6>Manage your Services Report</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="wordset">
                        <ul>
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('services-table','Services Report')"><i
                                        class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel" id="serviceReportExcelBtn"><i
                                        class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i
                                        class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>
                <form action="{{ url('services_report') }}" method="get" class="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">Search:</label>
                            <button type="submit" class="btn btn-warning">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table datanew serviceReport" id="services-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Service Name</th>
                                <th>Total Count</th>
                                <th>By Booking</th>
                                <th>By Walk-In</th>
                                <th>Total Earning</th>
                            </tr>
                        </thead>
                        <tbody id="services_report_tbody">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($serviceData as $data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data['service']->service_name}}</td>
                                <td>{{ $data['totalCount'] }}</td>
                                <td>{{ $data['totalBookings'] }}</td>
                                <td>{{ $data['totalWalkins'] }}</td>
                                <td>{{ $data['totalEarning'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
