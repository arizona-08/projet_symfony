<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function sendLocationDeletedNotification(string $clientEmail, string $locationRef): void
    {
        $email = (new Email())
            ->from('noreply@location-auto.com')
            ->to($clientEmail)
            ->subject('Suppression de votre location')
            ->html("
                <h1>Suppression de location</h1>
                <p>Votre location {$locationRef} a été supprimée.</p>
                <p>Pour toute question, n'hésitez pas à nous contacter.</p>
            ");

        $this->mailer->send($email);
    }
}