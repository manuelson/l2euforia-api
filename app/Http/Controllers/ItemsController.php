<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Items;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['showNewsUser', 'getList']]);
    }


    public function getList(Request $request)
    {
        try {

            $this->validate(
                $request,
                [
                    'type' => 'required',
                    'owner_id' => 'required'
                ],
            );

            $news = Items::Where('loc', $request->type)
                ->Where('owner_id', $request->owner_id)->orderBy('enchant_level', 'DES')->get();

            return response()->json(['message' => $news, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
        }
    }

}
