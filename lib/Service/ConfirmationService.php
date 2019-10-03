<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Service;

use Dawbed\UserBundle\Entity\UserInterface;
use DawBed\PHPUserActivateToken\Model\Criteria\CreateCriteria;
use DawBed\UserConfirmationBundle\Event\RefreshTokenEvent;
use DawBed\UserRegistrationConfirmationBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfirmationService
{
    private $container;
    private $contextFactoryService;

    function __construct(ContainerInterface $container, ContextFactoryService $contextFactoryService)
    {
        $this->container = $container;
        $this->contextFactoryService = $contextFactoryService;
    }

    public function prepareTokenCriteria(UserInterface $user): CreateCriteria
    {
        $tokenExpired = $this->container->getParameter(Configuration::TOKEN_EXPIRED_TIME_NODE);
        $context = $this->contextFactoryService->build(ContextFactoryService::REGISTRATION);

        return new CreateCriteria(new \DateInterval($tokenExpired), $user, $context);
    }

    public function prepareTokenEvent(UserInterface $user): RefreshTokenEvent
    {
        return new RefreshTokenEvent($this->prepareTokenCriteria($user));
    }
}