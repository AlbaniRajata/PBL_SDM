<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAdminController extends Controller
{
    public function index()
    {
        try {
            $users = UserModel::select('id_user', 'username', 'nama', 'tanggal_lahir', 
            'email', 'NIP', 'level')->get();
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = UserModel::select('id_user', 'username', 'nama', 'tanggal_lahir', 
                'email', 'NIP', 'level')
                ->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:t_user',
                'nama' => 'required',
                'tanggal_lahir' => 'required|date',
                'email' => 'required|email|unique:t_user',
                'password' => 'required|min:6',
                'NIP' => 'required|unique:t_user',
                'level' => 'required|in:admin,user,pimpinan,dosen'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], 422);
            }

            $user = UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'NIP' => $request->NIP,
                'level' => $request->level
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = UserModel::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:t_user,username,'.$id.',id_user',
                'nama' => 'required',
                'tanggal_lahir' => 'required|date',
                'email' => 'required|email|unique:t_user,email,'.$id.',id_user',
                'NIP' => 'required|unique:t_user,NIP,'.$id.',id_user',
                'level' => 'required|in:admin,user,pimpinan,dosen'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], 422);
            }

            $user->update([
                'username' => $request->username,
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'email' => $request->email,
                'NIP' => $request->NIP,
                'level' => $request->level
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $user = UserModel::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        try {
            $user = UserModel::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'email' => 'required|email|unique:t_user,email,'.$id.',id_user',
                'old_password' => 'required_with:new_password',
                'new_password' => 'nullable|min:5',
                'confirm_password' => 'same:new_password'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], 422);
            }

            // Check if password update is requested
            if ($request->filled('old_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Password lama tidak sesuai'
                    ], 422);
                }

                $updateData = [
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'NIP' => $request->NIP,
                    'password' => Hash::make($request->new_password)
                ];
            } else {
                $updateData = [
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'NIP' => $request->NIP
                ];
            }

            $user->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
