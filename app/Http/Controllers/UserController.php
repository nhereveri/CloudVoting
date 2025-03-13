<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\User;

class UserController extends Controller
{
    public function batchCreate(Request $request)
    {
        try {
            DB::beginTransaction();
            
            foreach ($request->users as $userData) {
                // Check if user already exists
                if (User::where('run', $userData['run'])->orWhere('email', $userData['email'])->exists()) {
                    throw new \Exception('USER_EXISTS', 409);
                }

                // Validate data
                if (!$userData['name'] || !$userData['run'] || !$userData['email']) {
                    throw new \Exception('INVALID_DATA', 400);
                }

                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'run' => $userData['run'],
                    'password' => Hash::make(Str::random(12))
                ]);

                Permission::create([
                    'user_id' => $user->id,
                    'can_vote' => true,
                    'is_supervisor' => false,
                    'is_admin' => false
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            $statusCode = 500;
            $message = 'Ha ocurrido un error interno';

            if ($e->getCode() === 409) {
                $statusCode = 409;
                $message = 'Algunos usuarios ya existen en el sistema';
            } elseif ($e->getCode() === 400) {
                $statusCode = 400;
                $message = 'Los datos ingresados no son válidos';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], $statusCode);
        }
    }
    
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = User::where('email', $request->email)->exists();
        
        return response()->json([
            'exists' => $exists
        ]);
    }
}
