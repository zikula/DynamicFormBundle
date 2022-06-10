FormTypes list
==============

The bundle provides all the [standard Symfony formTypes](https://symfony.com/doc/current/reference/forms/types.html)
that are reasonable to include in dynamically generated forms. You may want to amend, filter or trim the field type
list. An event is available for your app's consumption to do so. Listen for the 
`Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent` and modify the formType list as needed.
The initial list is also populated by an EventSubscriber with a _priority_ of 1000. Be sure your event subscriber has a
lower priority.

Adding Custom FormTypes
-----------------------

Adding a custom formType to your list is as simple as implementing an event Subscriber. The example below adds a
custom formType called `HairColor` and also removes the 'Currency' formType from the list. Multiple types can be added
or removed at the same time with `addChoices` and `removeChoices` methods.

```php
// src/EventSubscriber/CustomFormTypeSubscriber.php
namespace App\EventSubscriber;

use App\Form\HairColorType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent;

class CustomFormTypeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormTypeChoiceEvent::class => ['addFormTypes'],
        ];
    }

    public function addFormTypes(FormTypeChoiceEvent $event)
    {
        $event->addChoice('Other fields', 'Hair Color', HairColorType::class);
        $event->removeChoice('Choice fields', 'Currency');
    }
}
```
