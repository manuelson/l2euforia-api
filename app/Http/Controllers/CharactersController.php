<?php

namespace App\Http\Controllers;

use App\Models\Characters;

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
            return response()->json(['message' => $e->getMessage(), 'error' => false], 409);
        }
    }
}
