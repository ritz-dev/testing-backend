@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Management</h4>
                <h6>Update User</h6>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form action="{{ url('user-update/' . $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>User Name<span style="color: red"> *</span></label>
                                <input type="text" name="name" value="{{ $user->name }}" required>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email<span style="color: red"> *</span></label>
                                <input type="text" name="email" value="{{ $user->email }}">
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Phone<span style="color: red"> *</span></label>
                                <input type="text" name="phone" value="{{ $user->phone }}" required>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('phone') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Role<span style="color: red"> *</span></label>
                                <select class="form-select" name="role" aria-label="Default select example" required>
                                    <option @if ($user->role_id == 2) selected @endif value="2">Staff</option>
                                    <option @if ($user->role_id == 1) selected @endif value="1">Admin</option>
                                    <option @if ($user->role_id == 0) selected @endif value="0">Barber</option>
                                </select>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('role') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">  
                            
                            <div class="form-group">
                                <label>Old Password<span style="color: red"> *</span></label>
                                <div class="pass-group">
                                    <input type="password" class="pass-input" name="old_password" required>
                                    <div class="text-danger form-control-feedback">
                                        {{ $errors->first('old_password') }}
                                    </div>
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>New Password<span style="color: red"> *</span></label>
                                <div class="pass-group">
                                    <input type="password" class="pass-inputs" name="new_password" required>
                                    <span class="fas toggle-passworda fa-eye-slash"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password<span style="color: red"> *</span></label>
                                <div class="pass-group">
                                    <input type="password" class="pass-inputs" name="new_password_confirmation" required>
                                    <span class="fas toggle-passworda fa-eye-slash"></span>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label> Profile Picture</label>
                                <div class="image-upload image-upload-new">
                                    <input type="file">
                                    <div class="image-uploads">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/upload.svg" alt="img">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-lg-12">
                            <input type="submit" value="Submit" class="btn btn-success">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
