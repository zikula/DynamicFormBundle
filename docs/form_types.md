FormTypes list
==============

The bundle provides all the [standard Symfony formTypes](https://symfony.com/doc/current/reference/forms/types.html)
that are reasonable to include in dynamically generated forms. You may want to amend, filter or trim the field type
list. An event is available for your app's consumption to do so. Listen for the 
`Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent` and modify the formType list as needed.
The initial list is also populated by an EventSubscriber with a _priority_ of 1000. Be sure your event subscriber has a
lower priority.

When a formType is selected, the form is reloaded via jQuery and relevant formOptions are provided to the user. This is
accomplished with an event subscriber (`Zikula\Bundle\DynamicFormBundle\Form\EventListener\AddFormOptionsListener`).
All forms will have the following formOptions added: `required`, `priority`, `help`. Other formTypes add additional
options as well.

Provided Custom FormTypes
-------------------------
### ChoiceTypeTransformed
ChoiceTypeTransformed is the same as a standard ChoiceType, it just stores the response as a string. (see [data_storage](data_storage.md))

### ChoiceWithOtherType
ChoiceWithOtherType provides a standard ChoiceType field but adds an 'other' option. (see [data_storage](data_storage.md))

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

If your custom formType requires non-standard form options, you will need to add an EventSubscriber and create a
`formOptions` field based on the `formType` field. You can see 
`Zikula\Bundle\DynamicFormBundle\Form\EventListener\AddFormOptionsListener` and you can reference the 
[official Symfony doc](https://symfony.com/doc/current/form/dynamic_form_modification.html) for more information.
You should register your listener at a higher priority than the bundle's listener, and you should also `stopPropogation`
on the event after your action to prevent the bundle's listeners from also attempting to modify the form.
