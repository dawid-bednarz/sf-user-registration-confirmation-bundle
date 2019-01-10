<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Controller;

use DawBed\ComponentBundle\Event\Error\ExceptionErrorEvent;
use DawBed\ComponentBundle\Event\Error\FormErrorEvent;
use DawBed\ConfirmationBundle\EventListener\Token\TokenIsAlreadyConsumedException;
use DawBed\UserConfirmationBundle\Event\RefreshTokenEvent;
use DawBed\UserRegistrationConfirmationBundle\Event\Events;
use DawBed\UserRegistrationConfirmationBundle\Event\RefreshEvent;
use DawBed\UserRegistrationConfirmationBundle\Form\RepeatType;
use DawBed\ComponentBundle\Service\EventDispatcher;
use DawBed\UserRegistrationConfirmationBundle\Service\MailService;
use DawBed\UserRegistrationConfirmationBundle\Service\ConfirmationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfirmationController extends AbstractController
{
    public function repeat(
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcher $eventDispatcher,
        ConfirmationService $repeatService,
        MailService $mailService
    ): Response
    {
        $form = $this->createForm(RepeatType::class, null, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw new NotFoundHttpException();
        }
        if (!$form->isValid()) {
            return $eventDispatcher->dispatch(new FormErrorEvent(Events::REFRESH_CONFIRMATION_ERROR, $form))
                ->getResponse();
        }

        $user = $form->get('user')->getData();
        $criteria = $repeatService->prepareTokenCriteria($user);

        try {
            $refreshTokenEvent = new RefreshTokenEvent($criteria);
            $eventDispatcher->dispatch($refreshTokenEvent);
        } catch (TokenIsAlreadyConsumedException $exception) {
            return $eventDispatcher->dispatch(new ExceptionErrorEvent(Events::REFRESH_CONFIRMATION_ERROR, $exception))
                ->getResponse();
        }
        $response = $eventDispatcher->dispatch(new RefreshEvent($user))
            ->getResponse();

        $entityManager->flush();

        $activateToken = $refreshTokenEvent->getModel()->getEntity();
        $mailService->confirmation($user, $activateToken);

        return $response;
    }
}