@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Income/Expense Report</h4>
                <h6>Manage your Income/Expense Report</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="wordset">
                        <ul>
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('products-table','Products Report')"><i
                                        class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel" id="productReportExcelBtn"><i
                                        class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i
                                        class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>
                <form action="{{ url('expenses_report') }}" method="get" class="filter-form">
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
                                <th>Date</th>
                                <th>Description</th>
                                <th>Income</th>
                                <th>Expense</th>
                            </tr>
                        </thead>
                        <tbody id="services_report_tbody">
                            @php
                                $i = 1;
                            @endphp
                            @foreach($reportData as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['date'] }}</td>
                                <td>{{ $row['description'] }}</td>
                                <td>{{ $row['income'] > 0 ? $row['income'] : '-' }}</td>
                                <td>{{ $row['expense'] > 0 ? $row['expense'] : '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot style="background: #808080;font-weight:bold;">
                            <tr>
                                <th></th>
                                <th>Total Income </th>
                                <th></th>
                                <th>{{ $totalIncome }}</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Total Expense </th>
                                <th></th>
                                <th></th>
                                <th>{{ $totalExpense }}</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Total Profit </th>
                                <th></th>
                                <th></th>
                                <th>{{ $totalProfit }}</th>
                            </tr>
                        </tfoot>
                        

                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
