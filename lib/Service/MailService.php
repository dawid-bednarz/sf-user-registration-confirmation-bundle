<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Service;

use DawBed\OperationLimitBundle\Service\OperationLimitService;
use DawBed\PHPOperationLimit\Model\Criteria;
use DawBed\PHPUser\UserInterface;
use DawBed\PHPUserActivateToken\UserActivateTokenInterface;
use DawBed\UserRegistrationConfirmationBundle\Model\Mail\Confirmation;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailService
{
    private $container;
    private $swiftMailer;

    function __construct(
        \Swift_Mailer $swiftMailer,
        ContainerInterface $container
    )
    {
        $this->container = $container;
        $this->swiftMailer = $swiftMailer;
    }

    public function confirmation(UserInterface $user, UserActivateTokenInterface $activateToken)
    {
        $confirmationMail = $this->container->get(Confirmation::class);

        //$this->swiftMailer->send($confirmationMail->prepare($user, $activateToken));

    }
}