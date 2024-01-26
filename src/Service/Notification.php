<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\MailerInterface;


/**
 * Service d'envoi d'email
 * 
 * @author carteur007 <saatsafranklin@gmail.com>
 * @link https://github.com/carteur007
 */
class Notification
{
    private $mailer = null;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * Methode d'envoi d'email
     * 
     * @param mixed|string $from,
     * @param mixed|string $to,
     * @param mixed $subject,
     * @param mixed|string $template,
     * @param mixed $context
     * @return void
     */
    public function notify(
        $from,
        $to,
        $subject,
        $template,
        $context = null
    ) {
        //On cree le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("_emails/$template.html.twig")
            ->context($context);

        //On envoi le mail
        try {
            $this->mailer->send($email);
        } catch (HttpTransportException $error) {
            echo $error;
        }
    }
}
