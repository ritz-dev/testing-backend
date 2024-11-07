<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('users.newuser');
    }

    public function userStore(Request $request)
    {
        // dd($request->role);
        $validator = $request->validate([
            'name' => 'required|max:50',
            // 'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
            'phone'=>'required'
        ]);

        if ($validator) {
            $user = new Users;
            $user->uniqueid = UniqueIDController::generateUniqueID('user');
            $user->name = $request->name;
            if ($request->filled('email')) {
                $user->email = $request->email;
            }else{
                $user->email=null;
            }
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role;
            $user->phone = $request->phone;
            $user->save();
            return redirect('user-list')->with("success", 'New user created successfully');
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function userList()
    {
        $users = Users::get();
        return view('users.userlist', compact('users'));
    }

    public function destroy($id)
    {
        $user=Users::findOrFail($id);
        $user->delete();
        return redirect('user-list')->with("success", 'User deleted successfully');
    }

    public function edit($id)
    {
        $user = Users::where('id', $id)->get()->first();
        return view('users.edituser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'role' => 'required',
            'phone'=>'required'
        ]);
        if ($validator) {
            $user = Users::findOrFail($id);
            if (Hash::check($request->old_password,$user->password) && $request->new_password == $request->new_password_confirmation) {
                $user->name = $request->name;
                if ($request->filled('email')) {
                    $user->email = $request->email;
                }else{
                    $user->email=null;
                }
                $user->phone = $request->phone;
                $user->role_id = $request->role;
                $user->password = Hash::make($request->new_password);
                $user->save();
                return redirect('user-list')->with("success", 'User Information Updated');
            }else{
                return redirect()->back()->withErrors(['error' => 'Something wrong with information provided']);
            }
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
}
