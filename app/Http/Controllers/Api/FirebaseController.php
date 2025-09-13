<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;  // Asegúrate de importar el Log
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


class FirebaseController extends Controller
{

    protected $messaging;

    public function __construct()
    {
        $this->messaging = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/sistema-monitoreo-2c6d2-firebase-adminsdk-fbsvc-06af762628.json'))
            ->createMessaging();
    }

    public function sendToDevice(string $token, string $title, string $body, array $data = [])
    {
        $message = CloudMessage::fromArray([
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ]);

        return $this->messaging->send($message);
    }

    public function storeToken(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'token' => 'required|string',
            'platform' => 'nullable|string',
        ]);

        try {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Si el usuario no está autenticado, devolver un error
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Actualizar o crear el token en la base de datos
            DeviceToken::updateOrCreate(
                ['token' => $request->token],
                ['user_id' => $user->id, 'platform' => $request->platform ?? 'android']
            );

            // Responder con un mensaje de éxito
            return response()->json(['message' => 'Token registrado correctamente']);
        } catch (\Exception $e) {
            // Si ocurre cualquier excepción, devolver una respuesta con el error
            return response()->json([
                'error' => 'Hubo un problema al registrar el token FCM',
                'message' => $e->getMessage()
            ], 500);  // Código de error 500 para errores del servidor
        }
    }

    /**
     * Envía una notificación FCM a todos los dispositivos de la gestante indicada
     */
    public function notificar(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $tokens = $user->deviceTokens()->pluck('token')->toArray();
        if (empty($tokens)) {
            return response()->json(['error' => 'La gestante no tiene dispositivos registrados'], 422);
        }

        $errores = [];
        foreach ($tokens as $token) {
            try {
                $this->sendToDevice($token, $request->titulo, $request->descripcion);
            } catch (\Exception $e) {
                $errores[] = $token;
            }
        }

        if (count($errores) === count($tokens)) {
            return response()->json(['error' => 'No se pudo enviar la notificación a ningún dispositivo'], 500);
        }

        return response()->json(['message' => 'Notificación enviada correctamente']);
    }

}
