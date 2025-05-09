<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;




class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validate = $request->validate([
                "email" => "required|email|unique:users,email",
                "password" => "required|confirmed|min:8",
                "name" => "required",
                "type" => "in:student,admin,supervisor,committee_head",
                "department" => "required",
            ]);



            $user = User::create([
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "name" => $request->name,
                "type" => $request->type ?? 'student',
                "department" => $request->department,
            ]);


            if ($user) {
                return response()->json(['status' => 'success', 'message' => 'Successfully inserted data'], 201);
            }
        } 
        catch(ValidationException $validationException) {
            return response()->json(['status' => 'error', 'message' => $validationException->errors()], 400);

        }
        catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    //{post} form_data login api 
    public function login(Request $request)
    {
        try {
            $validate = $request->validate([
                "email" => "required|email",
                "password" => "required|min:8",
            ]);
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json(['status' => 'error', 'message' => 'unbale login invalid'], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('apiToken')->plainTextToken;
            return response()->json(['status' => 'success', 'token' => $token, 'role' => $user->type], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function profile()
    {
        try {
            $user = Auth::user();
            if ($user) {
                return response()->json(['status' => 'success', 'message' => 'true get data', 'data' => $user], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function logout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->currentAccessToken()->delete();
                return response()->json(['status' => 'sucsses', 'message' => 'logout sucssesfully'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function supervisor()
{
    try {
    
        $users = User::where('type', 'supervisor')->get();

        return response()->json([
            'status' => 'success', 
            'message' => 'Successfully retrieved supervisors', 
            'data' => $users 
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
}
