# DESCRIPTION
Part of "sf-user-registration-bundle" confirmation user registration
# INSTALATION
1 Add bundle route file to your main routes.yaml (config/routes.yaml)
```yaml
userRegistrationConfirmationBundle:
    prefix: user/registration/confirmation/
    resource: '@UserRegistrationConfirmationBundle/Resources/config/routes.yaml'
``` 
2 Register Listener (config/services.yaml)
```yaml
    App\EventListener\User\Registration\Confirmation\ErrorListener:
        tags:
            - { name: kernel.event_listener, event: !php/const DawBed\UserRegistrationConfirmationBundle\Event\Events::REFRESH_CONFIRMATION_ERROR }
    App\EventListener\User\Registration\RefreshConfirmationListener:
        tags:
            - { name: kernel.event_listener, event: !php/const DawBed\UserRegistrationConfirmationBundle\Event\Events::REFRESH_CONFIRMATION_SUCCESS }
```
3 Create user_registration_confirmation.yaml in your ~/config/packages directory
```yaml
dawbed_user_registration_confirmation_bundle:
     tokenExpiredTime: '1D'
     confirmation_mail:
         email: "portal@gmail.com"
         template: "confirmation_mail.html.twig"
     operation_limit_per_account:
         allowed: 1
         on_time: 'PT10M'
         for_time: 'PT30M'
```

## Example Handler Event
```php
namespace App\EventListener\User\Registration\Confirmation\ErrorListener;

class ErrorListener
{
    public function __invoke(AbstractErrorEvent $event)
    {
           $response = new Response();
   
           if ($event instanceof FormErrorEvent) {
               $event->getForm(); // form with error
           }
           if ($event instanceof ExceptionErrorEvent) {
               $event->getException();
           }
           $event->setResponse($response);
    }
}
```