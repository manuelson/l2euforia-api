<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login',
            'refresh',
            'logout',
            'register',
            'existUser',
            'fgChangePassword',
            'fgCheckToken'
        ]]);
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
                'email' => 'Email is not valid.',
                'email.required' => 'Email is required.',
                'password.required' => 'Password is required.'
            ]
        );


        try {
            $login = Account::where('email', $request->email)->first();
            if (!$login) {
                return response()->json(['message' => 'The user doest not exist.', 'error' => true], 200);
            } else {
                $user = Account::where('email', $request->email)
                    ->where('password', base64_encode(pack("H*", sha1(utf8_encode($request->password)))))
                    ->first();

                if (!$user) {
                    return response()->json(['message' => 'The username and password do not match.', 'error' => true], 200);
                }

                $token = Auth::login($user);

                return $this->respondWithToken($token, 'You have successfully logged in.', false, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    /**
     * @param Request $request
     * @param array $errors
     * @return array[]
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return ['message' => $errors, 'error' => true];
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
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6',
            ],
            [
                'login.unique' => 'That username already exists.',
                'email.unique' => 'There is already an account with that email.',
                'email' => 'You must enter a valid email.',
                'email.required' => 'Email is required.',
                'login.required' => 'Username is required',
                'password.min' => 'The password must have at least 6 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.required' => 'Password is required.'
            ]
        );
        try {
            $account = new Account();
            $account->login = $request->input('login');
            $account->email = $request->input('email');
            $password = $request->input('password');
            $account->password = base64_encode(pack("H*", sha1(utf8_encode($password))));

            $account->save();

            return response()->json([
                'message' => 'The account has been created successfully.',
                'error' => false,
                'account' => ['user_id' => $account->login, 'email' => $account->email]],
                200
            );
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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

        return response()->json(['message' => 'You have logged out successfully.'], 200);
    }

    /**
     * Refrescar token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), 'You have refreshed the session.', false, 200);
    }

    public function userProfile(Request $request)
    {
        $this->validate(
            $request,
            [
                'login' => 'required|string',
            ],
            [
                'login.required' => 'Username is required.'
            ]
        );


        try {
            $user = Account::where('login', $request->login)->first();
            if (!$user) {
                return response()->json(['message' => 'The user does not exist.', 'error' => true], 200);
            } else {
                return response()->json(['message' => $user, 'error' => false], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    public function existUser(Request $request)
    {
        $this->validate(
            $request,
            [
                'username' => 'required',
            ],
            [
                'username.required' => 'username is required.'
            ]
        );


        try {
            $user = Account::where('login', $request->username)->first();
            if (!$user) {
                return response()->json(['message' => ['There is no user in our system.'], 'error' => true], 200);
            }

            // Create token forgot password
            $user->fp_token = uniqid();
            $user->save();

            return response()->json(['message' => $user, 'error' => false], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    public function fgCheckToken(Request $request)
    {
        $this->validate(
            $request,
            [
                'token' => 'required'
            ]
        );

        try {
            $user = Account::where('fp_token', $request->token)->first();
            if (!$user) {
                return response()->json(['message' => 'The link has expired.', 'error' => true], 200);
            }

            return response()->json(['message' => 'Token valid.', 'error' => false], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    public function fgChangePassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'token' => 'required',
                'password' => 'required'
            ],
            ['token.required' => 'A token is required.']
        );


        try {
            $user = Account::where('fp_token', $request->token)->first();
            if (!$user) {
                return response()->json(['message' => 'The link has expired.', 'error' => true], 200);
            }

            // Create another token for security.
            $user->fp_token = '';
            $user->password = base64_encode(pack("H*", sha1(utf8_encode($request->password))));
            $user->save();

            return response()->json(['message' => 'The password has been changed successfully.', 'error' => false], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
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
                'email.required' => 'Email is required.',
                'email' => 'You must put a valid email.'
            ]
        );


        try {
            $user = Account::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['message' => 'User not exist.', 'error' => true], 200);
            } else {
                return response()->json(['message' => $user, 'error' => false], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
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
