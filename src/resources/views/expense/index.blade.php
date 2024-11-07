@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Expense List</h4>
                <h6>Manage Expense</h6>
            </div>
            <div class="button-container" style="display: flex; gap: 10px;">
                <div class="page-btn">
                    <a href="{{ route('expense_category.index') }}" class="btn btn-info text-white">
                        <i class="fas fa-eye me-1"></i>
                        Category List
                    </a>
                </div>
                <div class="page-btn">
                    <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#create_expense">
                        <i class="fas fa-plus" style="color: #fcfcfc;margin-right: 5px;"></i>
                        Add New Expense
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount (MMK)</th>
                                <th>Date</th>
				@if (auth()->user()->role_id==1)
                                   <th>Action</th>
				@endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $index => $expense)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>{{ $expense->description }}</td>
                                    <td>{{ number_format($expense->amount) }} MMK</td>
                                    <td>{{ date('d-m-Y', strtotime($expense->date)) }}</td>
				   @if (auth()->user()->role_id==1)
                                    <td>
                                        <a class="me-3" href="{{ url('/expense/' . $expense->uniqueid . '/edit') }}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>
                                        </a>

                                        <form action="{{ url('expense/' . $expense->uniqueid) }}" method="POST"
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

        <div class="modal fade" id="create_expense" tabindex="-1" aria-labelledby="create_expense" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Expense Category Form</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('expense.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Expense Category<span style="color: red"> *</span></label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="" selected disabled>Choose Category Type</option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->uniqueid }}">{{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="text-danger form-control-feedback">
                                            {{ $errors->first('category') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Date<span style="color: red"> *</span></label>
                                        <input type="date" name="date" id="date" class="form-control"
                                             required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Amount (MMK)<span style="color: red"> *</span></label>
                                        <input type="number" name="amount" id="amount" class="form-control"
                                            title="Please enter only numbers" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Description<span style="color: red"> *</span></label>
                                        <textarea type="text" name="description" id="description" class="form-control"
                                            title="Please enter only numbers" required></textarea>
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
