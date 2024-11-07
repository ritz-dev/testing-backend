@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Products Stock List</h4>
                <h6>Manage Products</h6>
            </div>
            <div class="button-container" style="display: flex; gap: 10px;">
                <div class="page-btn">
                    <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#sell_product">
                        <i class="fas fa-dollar-sign" style="color: #fcfcfc;margin-right: 5px;"></i>
                        Sell Product
                    </a>
                </div>
                <div class="page-btn">
                    <a href="{{ url('/products/create') }}" class="btn btn-added">
                        <i class="fas fa-plus" style="color: #fcfcfc;margin-right: 5px;"></i>
                        Add New Product
                    </a>
                </div>
            </div>

        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a class="btn btn-searchset">
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('products-table','Products List')"><i
                                        class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i
                                        class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i
                                        class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table datanew" id="products-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price (MMK)</th>
                                <th>Quantity Created</th>
                                <th>Stocks Left</th>
				@if (auth()->user()->role_id==1)
                                <th>Action</th>
				@endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="productimgname">
                                        <img src="{{ url('storage/product_photos/' . $product->photo) }}" alt="img"
                                            width="50px" class="rounded" style="height:50px;object-fit:cover">
                                        <span>{{ $product->product_name }}</span>
                                    </td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ number_format($product->price) }} MMK</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ $product->stocks_left }}</td>
				    @if (auth()->user()->role_id==1)
                                    <td>
                                        <a class="me-3" href="{{ url('products/edit/' . $product->uniqueid) }}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>
                                        </a>

                                        <form action="{{ url('products/delete/' . $product->uniqueid) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none;">
                                                <i class="fas fa-trash-alt fa-lg" style="color: #ff0000;"></i>

                                            </button>
                                        </form>

                                    </td>
				   @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="sell_product" tabindex="-1" aria-labelledby="sell_product" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Product Sold Form</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('products/sold/') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Product Name<span style="color: red"> *</span></label>
                                        <select class="form-select" id="product_select" name="product_select" required>
                                            <option value="" selected disabled>Choose Product</option>

                                            @foreach ($products_to_sold as $product)
                                                <option value="{{ $product->uniqueid }}">{{ $product->product_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="text-danger form-control-feedback">
                                            {{ $errors->first('barber_id') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <img src="{{asset('assets/images/barbericon.png')}}" id="product_photo" alt="img"
                                            class="rounded" style="height:100px;width:100px;object-fit:cover">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Date<span style="color: red"> *</span></label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            title="Please enter only numbers" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Quantity<span style="color: red"> *</span></label>
                                        <input type="number" name="qty" id="qty" class="form-control"
                                            title="Please enter only numbers" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="text" name="price" id="price" class="form-control"
                                            title="Please enter only numbers" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Total Amount</label>
                                        <input type="text" name="total_amount" id="total_amount" class="form-control"
                                            title="Please enter only numbers" required readonly>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2" type="submit">Submit</button>
                                <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
