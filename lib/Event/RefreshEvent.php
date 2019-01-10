<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Event;

use DawBed\ComponentBundle\Event\AbstractResponseEvent;
use DawBed\PHPUser\UserInterface;

class RefreshEvent extends AbstractResponseEvent
{
    private $user;

    function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getName(): string
    {
        return Events::REFRESH_CONFIRMATION_SUCCESS;
    }

}