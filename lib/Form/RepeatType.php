<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\Form;

use DawBed\UserBundle\Service\EntityService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RepeatType extends AbstractType
{
    private $userEntityService;

    function __construct(EntityService $userEntityService)
    {
        $this->userEntityService = $userEntityService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => $this->userEntityService->User,
                'choice_value' => 'email',
            ]);
    }

    public function getBlockPrefix()
    {
        return 'RepeatConfirmation';
    }
}