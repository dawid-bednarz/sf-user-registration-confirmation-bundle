<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\EventListener;

use DawBed\ComponentBundle\Service\EventDispatcher;
use DawBed\UserConfirmationBundle\Event\GenerateTokenEvent;
use DawBed\UserRegistrationBundle\Event\ResponseInterfaceEvent;
use DawBed\UserRegistrationConfirmationBundle\Service\ConfirmationService;
use DawBed\UserRegistrationConfirmationBundle\Service\MailService;
use DawBed\UserRegistrationConfirmationBundle\Model\OperationLimit;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationListener
{
    private $entityManager;
    private $eventDispatcher;
    private $mailService;
    private $confirmationService;
    private $operationLimit;

    function __construct(
        EventDispatcher $eventDispatcher,
        EntityManagerInterface $entityManager,
        ConfirmationService $confirmationService,
        MailService $mailService,
        OperationLimit $operationLimit
    )
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->mailService = $mailService;
        $this->confirmationService = $confirmationService;
        $this->operationLimit = $operationLimit;
    }

    function __invoke(ResponseInterfaceEvent $event)
    {
        $user = $event->getUser();
        $this->operationLimit->confirmation($user->getEmail());
        try {
            $this->entityManager->beginTransaction();
            $generateTokenEvent = new GenerateTokenEvent($this->confirmationService->prepareTokenCriteria($user));
            $this->eventDispatcher->dispatch($generateTokenEvent)
                ->getEntityManager()
                ->flush();
            //  $this->entityManager->commit();
            $activateToken = $generateTokenEvent->getModel()->getEntity();
            $this->mailService->confirmation($user, $activateToken);
        } catch (\Throwable $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}