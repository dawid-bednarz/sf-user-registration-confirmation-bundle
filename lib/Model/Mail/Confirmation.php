<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Model\Mail;

use DawBed\PHPUser\UserInterface;
use DawBed\PHPUserActivateToken\UserActivateTokenInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;

class Confirmation
{
    private $email;
    private $template;
    private $twigEngine;

    function __construct(string $email, string $template, TwigEngine $twigEngine)
    {
        $this->email = $email;
        $this->template = $template;
        $this->twigEngine = $twigEngine;
    }

    public function prepare(UserInterface $user, UserActivateTokenInterface $activateToken): \Swift_Message
    {
        return (new \Swift_Message())
            ->setTo($user->getEmail())
            ->setFrom($this->email)
            ->setSubject('Potwierdzenie rejestracji')
            ->setBody($this->twigEngine->render($this->template, [
                'token' => $activateToken->getToken()->getValue(),
                'user' => $user
            ]), 'text/html');
    }
}