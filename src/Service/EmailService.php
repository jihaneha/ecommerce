<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function sendNotificationEmail(string $recipient, string $subject, string $content)
    {
        $email = (new Email())
            ->from('ghamidou800@gmail.com')
            ->to($recipient)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}
