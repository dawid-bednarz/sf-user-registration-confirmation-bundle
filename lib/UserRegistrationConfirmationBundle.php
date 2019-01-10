<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle;

use DawBed\ComponentBundle\DependencyInjection\ChildrenBundle\ComponentBundleInterface;
use DawBed\UserRegistrationConfirmationBundle\DependencyInjection\UserRegistrationConfirmationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use DawBed\UserRegistrationConfirmationBundle\Event\Events;

class UserRegistrationConfirmationBundle extends Bundle implements ComponentBundleInterface
{
    public function getContainerExtension()
    {
        return new UserRegistrationConfirmationExtension();
    }

    public static function getEvents(): ?string
    {
        return Events::class;
    }

    public static function getAlias(): string
    {
        return UserRegistrationConfirmationExtension::ALIAS;
    }
}