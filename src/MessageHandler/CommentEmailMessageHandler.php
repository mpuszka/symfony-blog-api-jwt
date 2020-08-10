<?php

namespace App\MessageHandler;

use App\Message\CommentEmailMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;

final class CommentEmailMessageHandler implements MessageHandlerInterface
{   
    /**
     * Mailer object
     *
     * @var object
     */
    private $mailer;

    /**
     * Constructor
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    /**
     * Invoke method
     *
     * @param CommentEmailMessage $message
     * @return void
     */
    public function __invoke(CommentEmailMessage $message)
    {
        $email = $message->sendEmail();

        $this->mailer->send($email);
    }
}
