@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Expense Management</h4>
                <h6>Update Expense Information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url('expense/' . $expense->uniqueid) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Expense Category<span style="color: red"> *</span></label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" selected disabled>Choose Category Type</option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->uniqueid }}" @if($category->uniqueid === $expense->category_id) selected @endif>{{ $category->title }}
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
                                <input type="date" name="date" id="date" class="form-control" value="{{ $expense->date }}"
                                     required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Amount (MMK)<span style="color: red"> *</span></label>
                                <input type="number" name="amount" id="amount" class="form-control" value="{{ $expense->amount }}"
                                    title="Please enter only numbers" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Description<span style="color: red"> *</span></label>
                                <textarea type="text" name="description" id="description" class="form-control" aria-valuemax="{{ $expense->description }}"
                                    title="Please enter only numbers" required>{{ $expense->description }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12">
                        <button class="btn btn-submit me-2" type="submit">Update</button>
                        <a class="btn btn-cancel" href="{{ route('expense.index') }}">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
