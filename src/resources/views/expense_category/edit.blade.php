@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Expense Category Management</h4>
                <h6>Update Expense Category Information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url('expense_category/' . $category->uniqueid) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Title<span style="color: red"> *</span></label>
                                <input type="text" name="title" id="title" required value="{{ $category->title }}">

                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12">
                        <button class="btn btn-submit me-2" type="submit">Update</button>
                        <a class="btn btn-cancel" href="{{ route('expense_category.index') }}">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
