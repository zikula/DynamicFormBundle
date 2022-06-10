# Translatable Labels and Group names

This bundle provides for "inline" label and group name translation for all dynamic fields.
 - If a label text is not provided, the field name will be used (like normal text fields).
 - If a group name is not provided, the field is placed in the 'Default' group.
 - Any _configured_ language/locale will be shown in the dynamic field creation.

## Enable label translation

In your app, you must enable label and group name translation. Create a config file:

```yaml
# config/packages/zikula_dynamic_form_property.yaml

zikula_dynamic_form_property:
    translate: true
```

note: `translate` applies to both labels and group names.

## Add supported translation locales

You must indicate which locales you will support via an event subscriber. Create an event subscriber class:

```php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Bundle\DynamicFormBundle\Event\SupportedLocalesEvent;

class SupportedLocaleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SupportedLocalesEvent::class => 'addSupportedLocales'
        ];
    }

    public function addSupportedLocales(SupportedLocalesEvent $event)
    {
        $supportedLocales = $event->getSupportedLocales(); // [0 => 'default']
        $supportedLocales = array_merge($supportedLocales, ['de', 'es', 'fr_FR', 'fr_BE']);
        $event->setSupportedLocales($supportedLocales);
    }
}
```

If you are _not_ using the Symfony standard service definition defaults, you must tag the subscriber service definition
with `kernel.event_subscriber`.