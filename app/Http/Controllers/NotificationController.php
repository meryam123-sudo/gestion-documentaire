<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(
            Notification::with(['utilisateur', 'document'])
                ->orderBy('dateEnvoi', 'desc')
                ->get()
        );
    }

    public function notificationsUtilisateur($idUtilisateur)
    {
        return response()->json(
            Notification::with('document')
                ->where('idUtilisateur', $idUtilisateur)
                ->orderBy('dateEnvoi', 'desc')
                ->get()
        );
    }

    public function marquerCommeLue($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notification introuvable'
            ], 404);
        }

        $notification->update([
            'lue' => true
        ]);

        return response()->json([
            'message' => 'Notification marquée comme lue',
            'notification' => $notification
        ]);
    }
}