@extends('layouts.app')
@section('content')
    @php
	$currentDate = date('Y-m');
    @endphp
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Salary Report</h4>
                <h6>Manage your Sales Report</h6>
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
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('salaries-list','Salary Report')"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel" id="salaryReportExcelBtn"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>
		

                <form action="{{ url('salary_report') }}" method="get" class="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="month" class="form-control" id="start_date" name="start_date" value="{{ request()->start_date ?? $currentDate }}">
                            </div>
                        </div>
                       {{--  <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="month" class="form-control" id="end_date" name="end_date" value="{{ request()->end_date ?? $currentDate }}">
                            </div>
                        </div> --}}
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
                                
                                <th>Barber Name</th>
                                <th>Total Serviced</th>
                                <th>Total Earned</th>
                                <th>Commission Rate</th>
                                <th>Commission Earned</th>
                                <th>Barber Discount</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody id="salary_report_tbody">
                            @php
                                $i = 1;

                            @endphp
                            @foreach ($barberData as $value)
                                @php

                                    $colorCode = ["#F7F306","#FC0303","#F903FC","#06EFE3","#FB9727","#048CE7","#F91E98","#27FA01","#CED1BE","#E4F503"];
                                    if($value){
                                        $number = $i++;
                                        $notoverten = $number > 9 ? $number % 10 : $number;
                                        $color = $colorCode[$notoverten];
                                        // $color_value = 'border-left: 3px solid red';
                                    }

                                @endphp
                                @foreach ($value as $index => $data)

                                    <tr>

                                       {{--  <td style="border-left: 3px solid {{$color}};">{{ $number}}</td> --}}
                                        <td>{{ $data['barber']->barber_name }}</td>
                                        <td>{{ $data['totalServices'] }}</td>
                                        <td>{{ $data['totalEarned'] }}</td>
                                        <td>
                                            <form action="" class="barber-form-change">
                                                <input type="number" name="commission_rate" id="commission_rate{{$data['barber']->id}}" step="0.5" class="form-control w-50 text-muted" value="{{$data['commissionRate']}}" @if(auth()->user()->name != "Admin" && $data['status'] == 'confirm') disabled @endif>
                                                <input type="hidden" name="barber_id" value="{{$data['barber']->id}}">
                                                <input type="hidden" name="date" value="{{ $data['periodMonth'] }}">
                                                <input type="hidden" name="total_rate" value="{{ $data['totalEarned'] }}">
						<input type="hidden" name="auth_name" value="{{auth()->user()->name}}">
                                            </form>
                                        </td>
                                        <td id={{ $data['barber']->id }}>{{ $data['totalCommission'] }}</td>
                                        <td>{{ $data['totalDiscount'] }}</td>
                                        <td>
                                            <select name="booking_status" class="commission-rate form-select text-muted" id="rate{{$data['barber']->id}}" @if(auth()->user()->name != "Admin" && $data['status'] == 'confirm') disabled @endif>
                                                <option value="complete" @if ($data['status'] == 'confirm') selected @endif>
                                                    Confirm</option>
                                                <option value="cancel" @if ($data['status'] == 'unconfirm') selected @endif>
                                                    Unconfirm</option>
                                            </select>
                                        </td>
                                    </tr>

                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
