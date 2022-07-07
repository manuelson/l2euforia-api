<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['showNewsUser']]);
    }

    public function showNewsUser(Request $request)
    {
        try {
            $news = News::where('username', $request->input('username'))->get();

            return response()->json(['message' => $news, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    public function showNewId(Request $request)
    {
        try {
            $news = News::where('id', $request->input('id'))->get();

            return response()->json(['message' => $news, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    public function createNew(Request $request)
    {
        try {
            $staff = Auth::user()->accessLevel;
            if ($staff >= 8) {
                $this->validate(
                    $request,
                    [
                        'title' => 'required|string',
                        'new' => 'required|string'
                    ],
                    [
                        'title.required' => 'Se requiere un titulo.',
                        'new.required' => 'Se requiere de un texto de la noticia.'
                    ]
                );

                $news = new News();

                $news->title = $request->input('title');
                $news->username = Auth::user()->login;
                $news->new = $request->input('new');

                $news->save();

                return response()->json(['message' => $news, 'error' => false], 200);
            } else {
                return response()->json(['message' => "No tienes permiso para crear una noticia.", 'error' => true], 409);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    public function updateNew(Request $request)
    {
        try {
            $staff = Auth::user()->accessLevel;
            if ($staff >= 8) {
                $this->validate(
                    $request,
                    [
                        'id' => 'required|integer',
                    ],
                    [
                        'id.required' => 'Se requiere un id de noticia.',
                    ]
                );

                $news = News::where('id', $request->input('id'))->first();
                if ($news) {
                    $updateVariable = null;

                    if ($request->input('title')) {
                        $news->title = $request->input('title');
                        $updateVariable = 'el titulo';
                    }

                    if ($request->input('new')) {
                        $news->new = $request->input('new');
                        $updateVariable = 'el cuerpo de la noticia';
                    }

                    $news->save();


                    if (!is_null($updateVariable)) {
                        return response()->json(['message' => "Has actualizado " . $updateVariable . " correctamente.", 'error' => false], 200);
                    } else {
                        return response()->json(['message' => "No se ha actualizado nada.", 'error' => false], 200);
                    }
                } else {
                    return response()->json(['message' => "No existe noticia con ese id.", 'error' => true], 409);
                }
            } else {
                return response()->json(['message' => "No tienes permiso para crear una noticia.", 'error' => true], 409);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    public function deleteNew(Request $request)
    {
        try {
            $staff = Auth::user()->accessLevel;
            if ($staff >= 8) {
                $news = News::where('id', $request->input('id'))->first();
                if ($news) {
                    $news->delete();

                    return response()->json(['message' => "Se ha borrado la noticia correctamente.", 'error' => false], 200);
                } else {
                    return response()->json(['message' => "No existe noticia con ese id.", 'error' => true], 409);
                }
            } else {
                return response()->json(['message' => "No tienes permiso para crear una noticia.", 'error' => true], 409);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }
}
