<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function send(string $to, string $subject, string $template, array $context = []): void
    {
        $mail = (new TemplatedEmail())
            ->to(new Address($to))
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);
        try {
            $this->mailer->send($mail);
        } catch (TransportExceptionInterface $e) {
            dump($e->getMessage());
        }
    }
}
