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
        // Construir el mensaje con el token directamente
        $message = CloudMessage::new($token)  // Aquí pasamos el token directamente
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

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

            // Registrar un log indicando que el token se guardó correctamente
            Log::info("Token registrado correctamente para el usuario: " . $user->id);

            // Responder con un mensaje de éxito
            return response()->json(['message' => 'Token registrado correctamente']);
        } catch (\Exception $e) {
            // Si ocurre cualquier excepción, registrar el error y devolver una respuesta con el error
            Log::error('Error al registrar el token FCM: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(), // Puedes registrar más detalles sobre la solicitud si lo deseas
            ]);

            // Responder con un error detallado
            return response()->json([
                'error' => 'Hubo un problema al registrar el token FCM',
                'message' => $e->getMessage()
            ], 500);  // Código de error 500 para errores del servidor
        }
    }

}
