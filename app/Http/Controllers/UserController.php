<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function register(Request $request)
    {
        
            //Validated
            $validateUser = Validator::make($request->all(),[
                'name' => 'required|max:255',
                'email' => 'required|string|max:255|unique:users|email',
                'password' => [
                    'required', 
                    'string', 
                    'min:5', 
                    'confirmed', 
                ],
                'password_confirmation' => [
                    'required',
                    'string',
                    'min:5',
                ],
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=> 'validation error',
                    'errors'=> $validateUser->errors()
                ], 401);
            }

            $user = User::create(
                $request->all()
                // 'name'=>$request->name,
                // 'email'=>$request->email,
                // 'password'=> Hash::make($request->password),
            );

            $token = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status'  => true ,
                'message'=> 'User created successfully',
                // 'token'=>$user->createToken("API TOKEN")->plainTextToken
                "token"=>$token,
            ], 200);
        
    }


    public function login(Request $request)
    {
        
            //Validated
            $validateUser = Validator::make($request->all(),[
                'name' => 'required|max:255',
                'email' => 'required|string|max:255|email',
                'password' => [
                    'required', 
                    'string', 
                    'min:5', 
                    'confirmed', 
                ],
                'password_confirmation' => [
                    'required',
                    'string',
                    'min:5',
                ],
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=> 'validation error',
                    'errors'=> $validateUser->errors()
                ], 401);
            }

            if(Auth::attempt($request->all())){
                if(auth('sanctum')->check()){
                auth()->user()->tokens()->delete();
             }
            
                // 'name'=>$request->name,
                // 'email'=>$request->email,
                // 'password'=> Hash::make($request->password),
            
            $user = Auth::user();
            $token = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status'  => true ,
                'message'=> 'User logged in successfully',
                'user'=> $user,
                'token'=>$token,
                // 'token'=>$user->createToken("API TOKEN")->plainTextToken
                // "token"=>$token,
            ], 200);
        
    }

    }










    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users) ;
 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required',
            'password' => ['required', 'string', 'min:5', 'confirmed', 'regex:/^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[$@$!%?&])[A-Za-z\d$@$!%?&]+$/'],
            'password_confirmation' => ['required','string','min:5','regex:/^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[$@$!%?&])[A-Za-z\d$@$!%?&]+$/'],
        ]);
        $user =  User::create($request->all());
        return response()->json($user) ;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required',
            'password' => 'required',
          ]);
          $user = User::find($id);
          $user->update($request->all());
          return response()->json($user);   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json($user) ;
    }
}
