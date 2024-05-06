<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Enregistrer un nouvel utilisateur",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur enregistré avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Connecter un utilisateur",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserLoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur connecté avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in successfully"),
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Liste des utilisateurs",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Retourne la liste des utilisateurs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users) ;
 
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Créer un nouvel utilisateur",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Afficher un utilisateur spécifique",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retourne l'utilisateur spécifique",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user) ;
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Mettre à jour un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur mis à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
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

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Supprimer un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur supprimé avec succès"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json($user) ;
    }
}
