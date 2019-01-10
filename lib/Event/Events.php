<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Event;

use DawBed\ComponentBundle\Event\AbstractEvents;

class Events extends AbstractEvents
{
    const REFRESH_CONFIRMATION_ERROR = 'user.registration_confirmation.error';
    const REFRESH_CONFIRMATION_SUCCESS = 'user.registration_confirmation.refresh_success';

    const ALL = [
        self::REFRESH_CONFIRMATION_ERROR => self::REQUIRED,
        self::REFRESH_CONFIRMATION_SUCCESS => self::REQUIRED
    ];

    protected function getAll(): array
    {
        return self::ALL;
    }

}