<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Welcome;
use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use User;
use Common;
use Mail;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'register', 'me']]);
    }

    public function register(Request $request)
    {
        //Registo de dados genericos
        $folk = Common::createFolk($request);
        //Utilizador
        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'status' => $request->status,
            'folk_id' => $folk->id,
        ]);

        
        //Relacionamento
        // $user->syncRoles($request->roles);
        // $user->syncPermissions($request->permissions);
        $user->folk->partner;
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('token', 'user'));
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // $user = User::whereEmail($request->email)->with('folk')->with('permissions')->first();
        $user = User::whereEmail($request->email)->first();

        // $user->load(
        //     'folk.partner',
        //     'permissions',
        //     'roles'
        // );

        if (!$user)
            return response()->Json(['error' => 'Conta não se encontra registada'], 401);

        $token = JWTAuth::attempt($credentials);
        if ($user->status) {
            if ($token) {
                // Mail:to($user)->send(new Welcome($user));
                return response()->json(compact('token', 'user'));
            }
            return response()->Json(['error' => 'Certifique a sua palavra passe'], 401);
        }
        return response()->Json(['error' => 'Conta desativada, aguarde a sua ativação'], 401);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function payload()
    {
        return auth('api')->payload();
    }

    public function guard()
    {
        return Auth::Guard('api');
    }
}
