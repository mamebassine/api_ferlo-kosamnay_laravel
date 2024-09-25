<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommandePayee extends Mailable
{
    use Queueable, SerializesModels;

    public $ligneCommande;

    /**
     * Create a new message instance.
     *
     * @param mixed $ligneCommande
     */
    public function __construct($ligneCommande)
    {
        $this->ligneCommande = $ligneCommande;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre commande a été payée',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: "Votre commande a été payée.\nProduit: {$this->ligneCommande->produitBoutique->nom}\nQuantité: {$this->ligneCommande->quantite_totale}\nPrix Total: {$this->ligneCommande->prix_totale}",
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // Ajouter des pièces jointes si nécessaire
    }
}
