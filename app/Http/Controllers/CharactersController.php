<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Characters;
use Illuminate\Http\Request;

class CharactersController extends Controller
{


    public function __construct()
    {
    }

    public function showOnline()
    {
        try {
            $usersOnline = Characters::where('online', 1)->get();

            $count = $usersOnline->count();

            if ($count > 1) {
                return response()->json(['message' => $count . ' usuarios conectados.', 'error' => false], 200);
            } else {
                return response()->json(['message' => $count . ' usuario conectado.', 'error' => false], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    public function getCharactersByUser(Request $request)
    {
        try {
            $characters = Characters::where('account_name', $request->username)->get();

            return response()->json(['message' => $characters, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    public function getCharactersByEmail(Request $request)
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
            if ($user) {
                $characters = Characters::where('account_name', $user->login)->get();
                if ($characters->count() > 0) {
                    return response()->json(['message' => $characters, 'error' => false], 200);
                } else {
                    return response()->json(['message' => "No existen personajes para este usuario.", 'error' => true], 200);
                }
            } else {
                return response()->json(['message' => "No existe usuario con ese email.", 'error' => true], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }
}
