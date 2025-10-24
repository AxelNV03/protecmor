<?php

namespace App\Events;

use App\Models\Mensaje;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use SerializesModels;

    /**
     * El mensaje que se acaba de enviar.
     */
    public $mensaje;

    /**
     * Crear una nueva instancia del evento.
     */
    public function __construct(Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Canal al que se transmitirá el evento.
     */
    public function broadcastOn(): PrivateChannel
    {
        // Canal privado de la clase: clase.{id}
        return new PrivateChannel('clase.' . $this->mensaje->clase_id);
    }

    /**
     * Nombre del evento que se escucha en el front.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Datos que se enviarán al cliente (JavaScript).
     */
    public function broadcastWith(): array
    {
        return [
            'id'        => $this->mensaje->id,
            'user_id'   => $this->mensaje->usuario_id,
            'user_name' => $this->mensaje->user?->name ?? 'Usuario',
            'contenido' => $this->mensaje->contenido,
            'fecha'     => optional($this->mensaje->fecha_envio)->format('Y-m-d H:i:s'),
        ];
    }
}
