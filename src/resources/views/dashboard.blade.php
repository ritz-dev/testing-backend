@extends('layouts.app')
@section('content')
<form action="" method="GET">
    <div class="row p-5">
        <div class="col-md-4">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="end_date">Search</label>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </div>
</form>

    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('assets/images/dash1.svg') }}"
                                alt="img" /></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalIncome  + $totalSold}}">${{ number_format($totalIncome + $totalSold)}}</span> MMK
                        </h5>
                        <h6>
                            Total  income by Filter
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash1">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('assets/images/dash2.svg') }}"
                                alt="img" /></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalProfit }}">${{ number_format($totalProfit) }}</span> MMK
                        </h5>
                        <h6>Total Business Profit by Filter</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash2">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('assets/images/dash4.svg') }}"
                            alt="img" /></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalCommission }}">${{ number_format($totalCommission) }}</span> MMK
                        </h5>
                        <h6>Total Commission by Filter</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash3">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('assets/images/dash4.svg') }}"
                                alt="img" /></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalExpenses }}">${{ number_format($totalExpenses) }}</span> MMK
                        </h5>
                        <h6>Total Expenses by Filter</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-sm-6 col-12 d-flex">
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count">
                   <a href="{{ route('booking.index') }}" class="text-white text-decoration-none">
                    <div class="dash-counts">
                        <h4>{{ DB::table('booking')->where('date', date('Y-m-d'))->distinct('uniqueid')->count() }}</h4>
                        <h5>Appointments Today</h5>
                    </div>
                   </a>
                    <div class="dash-imgs">
                        <i data-feather="file-text"></i>
                    </div>
                </div>
            </div>
	    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count" style="background: rgba(0, 143, 251, 0.85)">
                                <a href="{{ route('booking.index') }}" class="text-white text-decoration-none">
                                <div class="dash-counts">
                                        <h4>{{ DB::table('walk_in_customers')->where('date', date('Y-m-d'))->distinct('uniqueid')->count() }}</h4>
                                        <h5>Walk In Today</h5>
                                </div>
                                </a>
                                <div class="dash-imgs">
                                        <i data-feather="file-text"></i>
                                </div>
                        </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das3">
                    <div class="dash-counts">
                        <h4>{{ $totalProfit }}</h4>
                        <h5>Total Business Profit this year</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="file"></i>
                    </div>
                </div>
            </div>

            <!-- <div class="col-lg-3 col-sm-6 col-12 d-flex">
            </div> -->
        </div>

        <div class="row">

            <div class="col-lg-7 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Total income this year</h5>
                    </div>
                    <div class="card-body">
                        <div id="s-bar"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Recently Added Products</h4>
                        <div class="dropdown">
                            <a href="" data-bs-toggle="dropdown" aria-expanded="false" class="dropset">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="{{ url ('products')}}"
                                        class="dropdown-item">Product List</a>
                                </li>
                                <li>
                                    <a href="{{ url ('products/create') }}"
                                        class="dropdown-item">Product Add</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Sno</th>
                                        <th>Products</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_products as $product)
                                    <tr>
                                        <td>{{ $product->uniqueid }}</td>
                                        <td class="productimgname">
                                            {{--  href="{{ url('product/'.$product->uniqueid) }}" --}}
                                            <div
                                                class="product-img">
                                                <img src="{{ url('storage/product_photos/'.$product->photo) }}"
                                                    alt="product" />
                                            </div>
                                            <span>{{ $product->product_name }}</span>
                                        </td>
                                        <td>{{ $product->price }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
