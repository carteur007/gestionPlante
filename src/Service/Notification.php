<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\MailerInterface;

class Notification  
{
    private $mailer = null;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    public function notify(
        $from, 
        $to, 
        $subject, 
        $template, 
        $context = null
        ){
            //On cree le mail
            $email = (new TemplatedEmail())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->htmlTemplate("_emails/$template.html.twig")
                ->context($context);

            //On envoi le mail
            try{
                $this->mailer->send($email);
            }catch(HttpTransportException $error){
                echo $error;
            }
        }
}











