@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Baber Performance Report</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('services-table','Services Report')"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel" id="salaryReportExcelBtn"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>
                <form action="{{ url('performance_report') }}" method="get" class="filter-form">
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
                    <table class="table datanew salaryReport" id="salaries-list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barber Name</th>
                                @foreach ($services as $service)
                                    <th>{{ $service->service_name }}</th>
                                @endforeach
                                <th>Total Serviced</th>
                            </tr>
                        </thead>
                        <tbody id="performance_report_tbody">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($performanceData as $barberPerformance)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $barberPerformance['barber_name'] }}</td>
                                @foreach ($services as $service)
                                    <td>{{ $barberPerformance['services'][$service->id] }}</td>
                                @endforeach
                                <td>{{ $barberPerformance['total_serviced'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
