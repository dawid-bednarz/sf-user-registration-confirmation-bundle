<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Service;

use DawBed\ContextBundle\Service\AbstractContextFactory;
use DawBed\ContextBundle\Service\CreateService;
use DawBed\ContextBundle\Service\CreateServiceInterface;
use DawBed\ContextBundle\Service\FactoryCollection;
use DawBed\ContextBundle\Service\EntityService;
use DawBed\PHPContext\ContextInterface;

class ContextFactoryService extends AbstractContextFactory
{
    const REGISTRATION = 1;
    const CONFIRMATION = 2;

    private $entityService;
    private $createService;

    public function __construct(CreateService $createService, EntityService $entityService)
    {
        $this->entityService = $entityService;
        $this->createService = $createService;
    }

    protected function getCreateService(): CreateServiceInterface
    {
        return $this->createService;
    }

    protected function getFactories(): FactoryCollection
    {
        return (new FactoryCollection())
            ->append(self::REGISTRATION, $this->confirmated())
            ->append(self::CONFIRMATION, $this->confirmationMail());
    }

    private function confirmated(): \Closure
    {
        return \Closure::bind(function (): ContextInterface {
            return (new $this->entityService->Context)
                ->setType(self::REGISTRATION)
                ->setName('Registration');
        }, $this);
    }

    private function confirmationMail(): \Closure
    {
        return \Closure::bind(function (): ContextInterface {
            return (new $this->entityService->Context)
                ->setType(self::CONFIRMATION)
                ->setName('Registration - send confirmation mail');
        }, $this);
    }
}