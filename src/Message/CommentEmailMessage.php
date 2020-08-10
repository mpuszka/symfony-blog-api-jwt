<?php

namespace App\Message;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class CommentEmailMessage
{
    /**
     * Comment title
     *
     * @var string
     */
    private $title;

    /**
     * Constructor
     *
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Get Title
     *
     * @return string
     */
   public function getTitle(): string
   {
       return $this->title;
   }

   /**
    * Send email method
    *
    * @return object
    */
   public function sendEmail(): object 
   {
       
    $email = (new TemplatedEmail())
        ->from('hello@example.com')
        ->to('you@example.com')
        ->subject('New Comment: ' . $this->title)
        ->htmlTemplate('emails/comment.html.twig')
        ->context([
            'title' => $this->title
        ]);

    return $email;
}
}
