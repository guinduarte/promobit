<?php

namespace App\Job;

use App\Notification\RequestPasswordNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class RequestPasswordNotificationJob implements MessageHandlerInterface
{
    function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(RequestPasswordNotification $message)
    {
        $payload = $message->getContent();

        $email = (new Email())
            ->from('test@promobit.com.br')
            ->to($payload['email'])
            ->subject('Reset password has been requested!')
            ->text('Your password request code has been created! code: ' .$payload['code'])
            ->html('<p>Your password request code has been created!</p><p>code: ' .$payload['code']. '</p>');

        $this->mailer->send($email);
    }
}