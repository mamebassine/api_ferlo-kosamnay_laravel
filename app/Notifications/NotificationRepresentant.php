<?php

namespace App\Notifications;

use App\Http\Controllers\LigneCommandeController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationRepresentant extends Notification
{
    use Queueable;
    private $ligneCommande;
    private $client;

    /**
     * Create a new notification instance.
     */
    public function __construct($ligneCommande, $client)
    {
        $this->ligneCommande= $ligneCommande;
        $this->client= $client;
 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $notifiable->id,
            'objet' => 'Nouvelle commande reçue',
            'description' => "Nouvelle commande de {$this->client->name} d'un montant de {$this->ligneCommande->prix_totale}",
            'lue' => 0
        ];
    }
}
