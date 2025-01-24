<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelOrderStatusChanged extends Notification
{
    use Queueable;

    protected $status;
    protected $travelOrder;

    // Passando os dados necessários para a notificação
    public function __construct($status, $travelOrder)
    {
        $this->status = $status;
        $this->travelOrder = $travelOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Definindo que a notificação será via e-mail
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Atualização do Pedido de Viagem')
                    ->line('O status do seu pedido de viagem nº ' .$this->travelOrder->id .' foi alterado para: ' . ucfirst($this->status) . '.')
                    ->line('Obrigado por usar nosso sistema!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'status' => $this->status,
            'travel_order_id' => $this->travelOrder->id,
        ];
    }
}
