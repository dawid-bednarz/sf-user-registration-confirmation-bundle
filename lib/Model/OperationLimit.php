<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Model;

use DawBed\ContextBundle\Provider;
use DawBed\OperationLimitBundle\Service\OperationLimitService;
use DawBed\PHPOperationLimit\Model\Criteria;
use DawBed\UserRegistrationConfirmationBundle\Enum\ContextEnum;

class OperationLimit
{
    private $operationLimitService;
    private $contextProvider;
    private $allowed;
    private $onTime;
    private $forTime;

    function __construct(
        Provider $contextProvider,
        OperationLimitService $operationLimitService,
        int $allowed,
        string $onTime,
        string $forTime
    )
    {
        $this->contextProvider = $contextProvider;
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
                ->setContext($this->contextProvider->build(ContextEnum::REGISTRATION_CONFIRMATION_MAIL))
                ->setAllowed($this->allowed)
                ->setOnTime($this->onTime)
                ->setForTime($this->forTime));
    }
}