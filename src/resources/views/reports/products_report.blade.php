@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Products Report</h4>
                <h6>Manage your Products Report</h6>
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
                <form action="{{ url('products_report') }}" method="get" class="filter-form">
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
                                <th>Product Name</th>
                                <th>Total</th>
                                <th>StockLeft</th>
                                <th>SoldCount</th>
                                <th>SoldAmount</th>
                            </tr>
                        </thead>
                        <tbody id="services_report_tbody">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($productReportData as $product)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['total'] }}</td>
                                <td>{{ $product['total'] - $product['total_count'] }}</td>
                                <td>{{ $product['total_count'] }}</td>
                                <td>{{ $product['total_amount'] }}</td>
                            </tr>
                            @endforeach 
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
