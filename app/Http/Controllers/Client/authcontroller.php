<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class authcontroller extends Controller
{
    public function register(Request $request){
        $data=[];
        $request->validate([
          "Firstname"=>'required|between:3,9',
          "Lastname"=>'required|between:3,9',
          "visaphoto"=>"required",
          "phone"=>'required|digits:10|unique:users,phone',
          "Nationalty"=>"required",
          "email"=>'required|unique:users,email|email',
          "password"=>"required|confirmed"
            ]);


           $image = $request->file('visaphoto');
           //return $image;
            // توليد اسم عشوائي للصورة
           $fileName = uniqid().'.'.$image->getClientOriginalExtension();
            // حفظ الصورة في مجلد التخزين (Storage) الذي تحدده
            Storage::disk('public')->put($fileName, file_get_contents($image));

              $user = new User();
              $user->Firstname =$request->Firstname;
              $user->Lastname =$request->Lastname;
              $user->visaphoto =Storage::url($fileName);
              $user->Nationalty =$request->Nationalty;
              $user->email =$request->email;
              $user->phone =$request->phone;
              $user->password =Hash::make($request->password);
              $user->save();

              $UserInfo=User::where('email',$request->email)->first();

              $token =$UserInfo->createToken('Register Token')->plainTextToken;

              $data['user']=$user;
              $data['token']=$token;

              return response()->json([
                "message"=> "seccefull Rigisetered",
                'Data'=>$data,
                'status'=>201
            ]);

    }
    public function login(Request $request){
        $request->validate([
            "email"=>'required|email',
            "password"=>'required'
          ]);
          $jsondecode=json_decode($request->password);
          if(strlen($jsondecode) <8 || strlen($jsondecode)>16){
            return response()->json([
                'Data'=>[],
                'Massage'=>'The  password must be between 8 and 16 please try again',
                'status'=>422
            ],422);
        }
        else{
            $data=[];
          if(!auth()->attempt($request->only(['email','password']))){
              return response()->json([
                  'Data'=>[],
                  'Massage'=>'The email or password is Not Correct please try again',
                  'status'=>500
              ],500);
          }
                      $info=User::where('email',$request->email)->first();
                      $token=$info->createToken("auth_token")->plainTextToken;
                  return response()->json([
                      'Data'=>$info,
                      'token'=>$token,
                      'message'=>'login succesfuly',
                      'status'=>200
                  ]);
        }
    }


    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return response()->json([
            'data'=>[],
            'Massage'=>'you logging out',
            'satatus'=>200
        ]);
    }
}