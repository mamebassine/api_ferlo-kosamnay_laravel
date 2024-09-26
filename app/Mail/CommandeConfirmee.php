<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommandeConfirmee extends Mailable
{
    use Queueable, SerializesModels;

    public $ligneCommande; // Propriété pour stocker la ligne de commande

    /**
     * Crée une nouvelle instance de message.
     *
     * @param mixed $ligneCommande
     */
    public function __construct($ligneCommande)
    {
        $this->ligneCommande = $ligneCommande; // Initialise la propriété avec la ligne de commande
    }

    /**
     * Obtient l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre commande a été confirmée', // Sujet de l'email
        );
    }

    /**
     * Obtient la définition du contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            text: "Votre commande a été confirmée.\nProduit: {$this->ligneCommande->produitBoutique->nom}\nQuantité: {$this->ligneCommande->quantite_totale}\nPrix Total: {$this->ligneCommande->prix_totale}",
        );
    }

    /**
     * Obtient les pièces jointes pour le message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // Ajouter des pièces jointes si nécessaire
    }
}
