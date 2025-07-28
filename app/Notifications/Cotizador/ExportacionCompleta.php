<?php

namespace App\Notifications\Cotizador;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportacionCompleta extends Notification
{
    use Queueable;

    public function __construct(
        public string $rutaArchivo,
        public string $nombreArchivo
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Exportación completada',
            'message' => 'Tu archivo de inventario está listo para descargar.',
            'link' => route('descargar.exportacion', ['archivo' => $this->nombreArchivo]),
            'ruta' => $this->rutaArchivo
        ];
    }
}
