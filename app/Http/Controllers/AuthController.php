<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AuthCollection;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function index()
    {
        $users=User::all();
        return new AuthCollection($users);
    }

    public function login(Request $request)
    {
        $validatedData=Validator::make($request->all(),
        [
            'UserName'=>'required',
            'Password'=>'required'

        ]);
        if($validatedData->fails()){
            return response()->json([
              'status'=>400,
              'errors'=>$validatedData->errors()
            ],400);
        }

        $user = User::where('UserName', $request->UserName)->first();

        if(!$user){
            return response()->json([
              'message' => 'unknown user' 
            ],400);
        }
        if(!Hash::check($request->Password, $user->Password)){
            return response()->json([
              'message' => 'Incorrect password'
            ],400);
          }
          

          $token = $user->createToken("API TOKEN")->plainTextToken;

          $user->AccessToken = $token;
          $user->save();
      
          return response()->json([
              'status' => 200,
              'message' => 'Connexion réussie',
              'token' => $token,
              'user' => $user
          ], 200);
    }

    public function register(Request $request){
        $validatedData=Validator::make($request->all(),
        [
            "BranchFId"=>'required',
            "UserName"=>'required|unique:TUsers',
            "Password"=>'required',
            "SerialNumber"=>'required',
            "IsAdmin"=>'required'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>400,
                'message'=>'Inscription echouée',
                'errors'=>$validatedData->errors()
            ],400);
        }

        $user=User::create([
            'UserName'=>$request->UserName,
            'Password'=>bcrypt($request->Password),
            'SerialNumber'=>$request->SerialNumber,
            'IsAdmin'=>$request->IsAdmin,
            'BranchFId'=>$request->BranchFId

        ]);
        return response()->json([
            'status'=>200,
            'message'=>'Inscription reussie',
            'token'=>$user->createToken("API TOKEN")->plainTextToken
        ],200);
    }

    public function logout() {
    
        auth()->user()->tokens()->delete();
      
        return response()->json([
          'status' => 200,
          'message' => 'Deconnecté'
        ],200);
    }

    
}
