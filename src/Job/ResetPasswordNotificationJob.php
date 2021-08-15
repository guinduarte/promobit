<?php

namespace App\Job;

use App\Notification\ResetPasswordNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class ResetPasswordNotificationJob implements MessageHandlerInterface
{
    function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(ResetPasswordNotification $message)
    {
        $payload = $message->getContent();

        $email = (new Email())
            ->from('test@promobit.com.br')
            ->to($payload['email'])
            ->subject('Your password has been changed!')
            ->text('Your password has been changed!')
            ->html('<p>' .$payload['name']. ', your password has been changed!!</p>');

        $this->mailer->send($email);
    }
}