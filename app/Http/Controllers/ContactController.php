<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{


    public function __construct()
    {
    }

    public function showContact(Request $request)
    {
        try {
            $contact = Contact::where('id', $request->id)->get();

            return response()->json(['message' => $contact, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    public function createContact(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'email' => 'required|email',
                    'reason' => 'required|string',
                    'content' => 'required|string'
                ],
                [
                    'email.required' => 'Se requiere un email.',
                    'email' => 'Debes poner un email valido.',
                    'reason.required' => 'Se requiere de un asunto.',
                    'content.required' => 'Se requiere de un contenido.'
                ]
            );

            $contact = new Contact();

            $contact->email = $request->email;
            $contact->reason = $request->reason;
            $contact->content = $request->content;
            $contact->status = 0;

            $contact->save();

            return response()->json(['message' => $contact, 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }

    public function updateContact(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'id' => 'required|integer',
                    'status' => 'required|integer'
                ],
                [
                    'id.required' => 'Se requiere un id de noticia.',
                    'status.required' => 'Se requiere un estado.'
                ]
            );

            $contact = Contact::where('id', $request->id)->first();
            if ($contact) {
                $updateVariable = null;

                if ($request->status) {
                    $contact->new = $request->status;
                    $updateVariable = 'el estado del contacto';
                }

                $contact->save();


                if (!is_null($updateVariable)) {
                    return response()->json(['message' => "Has actualizado " . $updateVariable . " correctamente.", 'error' => false], 200);
                } else {
                    return response()->json(['message' => "No se ha actualizado nada.", 'error' => false], 200);
                }
            } else {
                return response()->json(['message' => "No existe contacto con ese id.", 'error' => true], 409);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 409);
        }
    }
}
