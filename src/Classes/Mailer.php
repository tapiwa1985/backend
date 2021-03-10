<?php

namespace App\Classes;

use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface as MailerInterface;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * Mailer constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $email
     * @param string $name
     * @throws Exception
     */
    public function sendConfirmationMail(string $email, string $name)
    {
        try {
            $email = (new TemplatedEmail())
                ->from('thomas@yahoo.com')
                ->to($email)
                ->addTo('thomaskangai@ymail.com')
                ->subject('New Contact Created!')
                ->htmlTemplate("emails/mymail.html.twig")
                ->context([
                    'name' => $name,
                ]);
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }
}