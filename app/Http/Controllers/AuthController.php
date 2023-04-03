<?php

namespace App\Http\Controllers;

use App\Models\PersonalToken;
use App\Models\User;

use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'email' => 'required| unique:users',
            'password' => 'required| min:8',
            'telp' => 'required| unique:users',
            'alamat' => 'required',
            'jabatan' => 'required',
        ]);

        $nama = $request->input('nama');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $telp = $request->input('telp');
        $alamat = $request->input('alamat');
        $jabatan = $request->input('jabatan');

        $user = User::create([
            'nama' => $nama,
            'email' => $email,
            'password' => $password,
            'telp' => $telp,
            'alamat' => $alamat,
            'jabatan' => $jabatan
        ]);


        if($user){
            $result = [
                'code' => 200,
                'message' => 'Registrasi berhasil',
                'data' => $user
            ];
        }else{
            $result = [
                'code' => 400,
                'message' => 'User gagal ditambahkan',
            ];
        }

        return response()->json($result, 200);
    }


    public function login(Request $request)
    {

        $validasi = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

        if($validasi->fails()){
            return $this->error($validasi->errors()->first());
        }

        $user = User::where('email', $request->email)->first();
        if($user){
            
            if(password_verify($request->password, $user->password)){
                $generateToken = Str::random(32);

                $exist = PersonalToken::where('id_user', $user->id)->first();

                if ($exist == null) {
                    $token = PersonalToken::create([
                        'token' => $generateToken,
                        'id_user' => $user->id
                    ]);            
                } else {
                    $token = PersonalToken::where('id_user', $user->id)->first();
                    $token->token = $generateToken;
                    $token->save();
                }

        

                $user->token = $token->token;

                return $this->success($user);
            }
            else{
                return $this->error("Password anda salah");
            }
        }
        return $this->error("Email atau Password anda salah");
    }


    public function success($data, $message = "Login Berhasil"){
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error($message){
        return response()->json([
            'code' => 400,
            'message' => $message
        ], 400);

    }
}

