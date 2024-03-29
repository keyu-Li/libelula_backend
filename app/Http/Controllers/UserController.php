<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
class UserController extends Controller
{
    public function username()
    {
        return 'mobile';
    }

    // User Register
    public function register(Request $request) {

        $validator  =   Validator::make($request->all(), [
            'email' => 'required|min:6',
            'password' => 'required|min:6',
             'role' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }
        if ($request->username){
            $validator = Validator::make($request->all(), [
                'username' => 'min:6',
            ]);
        }
        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }
        if ($request->national_code){
            $validator = Validator::make($request->all(), [
                'national_code' => 'min:10|max:10',
            ]);
        }
        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }
        if ($request->email){
            $validator = Validator::make($request->all(), [
                'email' => 'email',
            ]);
        }
        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }
        try{
            $inputs = $request->all();
            $inputs["password"] = Hash::make($request->password);
            $user   =   User::where('email', $request->email)->first();
            if($user) return response()->json(["status" => "duplicate email", "message" => "Registration failed!"]);
            $user   =   User::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'national_code' => $request->national_code,
                'mobile' => $request->mobile,
                'email_verified_at' => Carbon::now(),
                'county' => $request->county,
                'city' => $request->city,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'email'  => $request->email,
                'status' => 'active',
                'password' => $inputs["password"],
                 'role' => $request->role,
            ]);

//            $user->assignRole($request->role);

            if(!is_null($user)) {
                return response()->json(["status" => "success", "message" => "Success! registration completed", "data" => $user]);
            }
            else {
                return response()->json(["status" => "failed", "message" => "Registration failed!"]);
            }
        }catch (\Exception $exception){
            return response()->json(["status" => "failed", "message" => $exception]);
        }

    }

    // User login
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            "email" =>  "required",
            "password" =>  "required",
        ]);

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $user           =       User::where("email", $request->email)->first();

        if(is_null($user)) {
            return response()->json(["status" => "failed", "message" => "Failed! user not found"]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
//            return User::where('mobile', $request->mobile)->first();
            $user       =       Auth::user();

            $token      =       $user->createToken('token')->plainTextToken;
            return response()->json(["status" => "success", "login" => true, "token" => $token, "data" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! invalid password"]);
        }
    }


    // User Detail
    public function me() {
        $user       =       Auth::user();
        if(!is_null($user)) {
            $data = [
                'id' => $user->id,
                'name' => ($user->first_name || $user->last_name) ? (ucfirst($user->first_name ?? "") . ucfirst($user->last_name ? " " . $user->last_name : "")) : null,
                'username' => $user->username ?? "",
                'avatar' => thumbImage($user->getDetailValue('avatar') ?? Constants::DEFAULT_AVATAR_PATH),
                'mobile' => isset($user->phone) ? $user->phone : null,
                'email' => $user->email ?? null,
                'isPhoneVerified' => $user->is_phone_verified == 1,
                'role' => $user->role ?? 0,
            ];
            return 'test';
            return response()->json(["status" => "success", "data" => $data]);
        }

        else {
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }
    }

    //userList
    public function users(){

        $users       =       User::with(['county','city'])->paginate(10);
        return response()->json(["status" => "success", "data" => $users]);
    }

    //userModify
    public function modify(Request $request){
        $user = User::find($request->id);
        if(!is_null($user)) {
            DB::beginTransaction();
            if($request->f_name) $user->f_name = $request->f_name;
            if($request->l_name) $user->l_name = $request->l_name;
            if($request->national_code) $user->national_code = $request->national_code;
            if($request->mobile_verified_at) $user->mobile_verified_at = $request->mobile_verified_at;
            if($request->county) $user->county = $request->county;
            if($request->city) $user->city = $request->city;
            if($request->address) $user->address = $request->address;
            if($request->postal_code) $user->postal_code = $request->postal_code;
            if($request->avatar) $user->avatar = $request->avatar;
            if($request->email) $user->email = $request->email;
            if($request->status) $user->status = $request->status;
            if($request->password) $user->password = $request->password;
            if($request->role) $user->role = $request->role;
            $user->save();

            $user->syncRoles($request->role);

            DB::commit();

            return response()->json(["status" => "success", "data" => $user]);
        }
        else {
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }
    }

    //search user
    public function search(Request $request){
        $status = $request->status;
        $mobile = $request->mobile;
        $email = $request->email;
        $id = $request->id;
        $role = $request->role;
        $branch = $request->branch;
        $username = $request->username;
        $national_code = $request->national_code;
        $county = $request->county;
        $city = $request->city;
        $l_name = $request->l_name;

        try{
            $list = User::
                when($status, function ($q, $status) {
                    return $q->where('status', $status);
                })
                ->when($mobile, function ($q, $mobile) {
                    return $q->where('mobile', $mobile);
                })
                ->when($email, function ($q, $email) {
                    return $q->where('email', $email);
                })
                ->when($id, function ($q, $id) {
                    return $q->where('id', $id);
                })
                ->when($role, function ($q, $role) {
                    return $q->where('role_id', $role);
                })
                ->when($username, function ($q, $username) {
                    return $q->where('username', $username);
                })
                ->when($national_code, function ($q, $national_code) {
                    return $q->where('national_code', $national_code);
                })
                ->when($county, function ($q, $county) {
                    return $q->where('county', $county);
                })
                ->when($city, function ($q, $city) {
                    return $q->where('city', $city);
                })
                ->when($l_name, function ($q, $l_name) {
                    return $q->where('l_name', $l_name);
                })
                ->with('county', 'city')
                ->orderBy('created_at')
                ->paginate(10);

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $list
            ];
            return response()->json($response);

        }catch(Exception $e){
            return response($e, 202);
        }
    }
}
