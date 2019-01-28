<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Model;

use DawBed\OperationLimitBundle\Service\OperationLimitService;
use DawBed\PHPOperationLimit\Model\Criteria;
use DawBed\UserRegistrationConfirmationBundle\Service\ContextFactoryService;

class OperationLimit
{
    private $operationLimitService;
    private $contextFactoryService;
    private $allowed;
    private $onTime;
    private $forTime;

    function __construct(
        ContextFactoryService $contextFactoryService,
        OperationLimitService $operationLimitService,
        int $allowed,
        string $onTime,
        string $forTime
    )
    {
        $this->contextFactoryService = $contextFactoryService;
        $this->operationLimitService = $operationLimitService;
        $this->allowed = $allowed;
        $this->onTime = $onTime;
        $this->forTime = $forTime;
    }

    public function canConfirmationOrException(string $name): void
    {
        $this->operationLimitService->makeByCriteria(
            (new Criteria())
                ->setName($name)
                ->setContext($this->contextFactoryService->build(ContextFactoryService::CONFIRMATION))
                ->setAllowed($this->allowed)
                ->setOnTime($this->onTime)
                ->setForTime($this->forTime));
    }
}