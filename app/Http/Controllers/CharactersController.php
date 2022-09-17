<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Characters;
use App\Models\Items;
use Illuminate\Http\Request;

class CharactersController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
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

    public function changeNickname(Request $request)
    {
        $this->validate(
            $request,
            [
                'nickname' => 'required',
                'original_username' => 'required',
                'account' =>  'required',

            ]
        );

        try {
            $characters = Characters::where('account_name', $request->account)
                ->where('char_name', $request->original_username)
                ->first();

            $exist = Characters::where('char_name', $request->nickname)->first();
            if ($exist) {
                throw new \Exception('The user with name: '. $request->nickname .' already exist.');
            }

            if ($charId = $characters->getAttribute('charId')) {
                // remove tokens to sex changes
                $item = Items::
                where('owner_id', $charId)
                    ->where('item_id', '60009')
                    ->where('loc', 'INVENTORY')
                    ->first();

                if ($item->getAttribute('count') < 10) {
                    throw new \Exception('You dont have enough Euphoria tokens.');
                }
                $item->count = (int)$item->getAttribute('count') - (int)10;
                $item->timestamps=false;
                $item->save();
            }

            $characters->char_name = $request->nickname;
            $characters->timestamps=false;
            $characters->save();
            return response()->json(['message' => $characters, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }

    }


    public function getTokens(Request $request)
    {
        $this->validate(
            $request,
            [
                'charId' => 'required'
            ]
        );

        try {
            $item = Items::
            where('owner_id', $request->charId)
                ->where('item_id', '60009')
                ->where('loc', 'INVENTORY')
                ->first();

            $count = 0;
            if ($item) {
                $count = (int)$item->getAttribute('count');
            }

            return response()->json(['message' => $count, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    public function getCharactersByUser(Request $request)
    {
        $this->validate(
            $request,
            [
                'username' => 'required'
            ]
        );

        try {
            $characters = Characters::where('account_name', $request->username)->get();

            return response()->json(['message' => $characters, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

    public function getList(Request $request)
    {
        try {
            $characters = Characters::orderBy('level', 'desc')->get();

            return response()->json(['message' => $characters, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }



    public function addNobless(Request $request)
    {
        $this->validate(
            $request,
            [
                'userId' => 'required',
                'username' => 'required'
            ]
        );

        try {
            $characters = Characters::where('charId', $request->userId)->where('account_name', $request->username)->first();

            if ($characters->getAttribute('online') === 1) {
                throw new \Exception('disconnect the user from the server first');
            }

            if ($charId = $characters->getAttribute('charId')) {
                // remove tokens to sex changes
                $item = Items::
                where('owner_id', $charId)
                    ->where('item_id', '60009')
                    ->where('loc', 'INVENTORY')
                    ->first();

                if ($item->getAttribute('count') < 10) {
                    throw new \Exception('you dont have enough Euphoria tokens.');
                }
                $item->count = (int)$item->getAttribute('count') - (int)10;
                $item->timestamps=false;
                $item->save();
            }

            if ($characters->getAttribute('nobless') == 1) {
                throw new \Exception('You already nobless.');
            }

            $characters->nobless = 1;
            $characters->timestamps=false;
            $characters->save();
            return response()->json(['message' => $characters, 'error' => false], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }

    }

    public function changeSex(Request $request)
    {
        $this->validate(
            $request,
            [
                'userId' => 'required',
                'username' => 'required'
            ]
        );

        try {
            $characters = Characters::where('charId', $request->userId)->where('account_name', $request->username)->first();

            if ($characters->getAttribute('online') === 1) {
                throw new \Exception('disconnect the user from the server first');
            }

            if ($charId = $characters->getAttribute('charId')) {
                // remove tokens to sex changes
                $item = Items::
                where('owner_id', $charId)
                    ->where('item_id', '60009')
                    ->where('loc', 'INVENTORY')
                    ->first();

                if ($item->getAttribute('count') < 5) {
                    throw new \Exception('you dont have enough Euphoria tokens.');
                }
                $item->count = (int)$item->getAttribute('count') - (int)5;
                $item->timestamps=false;
                $item->save();
            }

            if ($characters->getAttribute('sex') == 0) {
                $characters->setSex(1);
                $characters->timestamps=false;
                $characters->save();
                return response()->json(['message' => $characters, 'error' => false], 200);
            }

            if ($characters->getAttribute('sex') == 1) {
                $characters->setSex(0);
                $characters->timestamps=false;
                $characters->save();
                return response()->json(['message' => $characters, 'error' => false], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }

    }

    public function searchItems(Request $request)
    {
        $this->validate(
            $request,
            [
                'userId' => 'required',
                'itemId' => 'required',
            ]
        );

        try {
            $item = Items::
            where('owner_id', $request->userId)
                ->where('item_id', $request->itemId)
                ->where('loc', 'INVENTORY')
                ->first();

            if ($item) {
                return  response()->json(['message' => ['tokens' => $item->getAttribute('count') ], 'error' => false], 200);
            } else {
                return response()->json(['message' => ['tokens' => 0 ], 'error' => false], 200);
            }
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
