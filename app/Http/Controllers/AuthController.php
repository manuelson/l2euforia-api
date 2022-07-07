<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {

        $this->validate(
            $request,
            [
                'email' => 'required|email',
                'password' => 'required|string',
            ],
            [
                'email' => 'Debes poner un email valido.',
                'email.required' => 'Se requiere un email.',
                'password.required' => 'Se requiere de una contraseña.'
            ]
        );


        try {
            $login = Account::where('email', $request->email)->first();
            if (!$login) {
                return response()->json(['message' => 'No existe el usuario.', 'error' => true], 401);
            } else {
                $user = Account::where('email', $request->email)
                    ->where('password', base64_encode(pack("H*", sha1(utf8_encode($request->password)))))
                    ->first();

                if (!$user) {
                    return response()->json(['message' => 'No coincide el usuario y contraseña.', 'error' => true], 401);
                }

                $token = Auth::login($user);

                return $this->respondWithToken($token, 'Se ha logeado correctamente.', false, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {

        $this->validate(
            $request,
            [
                'login' => 'required|string|unique:accounts',
                'email' => 'required|email|unique:accounts',
                'password' => 'required|confirmed|min:6',
            ],
            [
                'login.unique' => 'Ya existe ese id de usuario.',
                'email.unique' => 'Ya existe una cuenta con ese email.',
                'email' => 'Debes poner un email valido.',
                'email.required' => 'Se requiere un email.',
                'login.required' => 'Se requiere un id de usuario.',
                'password.min' => 'Debe tener minimo 6 caracteres la contraseña.',
                'password.confirmed' => 'No concuerda la confirmación de contraseña.',
                'password.required' => 'Se requiere de una contraseña.'
            ]
        );
        try {
            $account = new Account();
            $account->login = $request->input('login');
            $account->email = $request->input('email');
            $password = $request->input('password');
            $account->password = base64_encode(pack("H*", sha1(utf8_encode($password))));

            $account->save();

            return response()->json(['message' => 'Se ha creado la cuenta correctamente', 'error' => false, 'account' => ['user_id' => $account->login, 'email' => $account->email]], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }


    /**
     * Deslogueo cuenta.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Has cerrado sesion correctamente'], 200);
    }

    /**
     * Refrescar token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), 'Has refrescado la sesion', false, 200);
    }

    public function userProfile(Request $request)
    {
        $this->validate(
            $request,
            [
                'login' => 'required|string',
            ],
            [
                'login.required' => 'Se requiere un id de usuario.'
            ]
        );


        try {
            $user = Account::where('login', $request->login)->first();
            if (!$user) {
                return response()->json(['message' => 'No existe el usuario.', 'error' => true], 401);
            } else {
                return response()->json(['message' => $user, 'error' => false], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    public function userProfileByEmail(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => 'Se requiere un email.',
                'email' => 'Debes de poner un email válido.'
            ]
        );


        try {
            $user = Account::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['message' => 'No existe el usuario.', 'error' => true], 401);
            } else {
                return response()->json(['message' => $user, 'error' => false], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    /**
     * Estructura al devolver el token
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $message, $error, $status)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'message' => $message,
            'error' => $error,
            'expires_in' => auth()->factory()->getTTL()
        ], $status);
    }
}
