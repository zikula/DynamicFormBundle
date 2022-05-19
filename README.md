DynamicFormPropertyBundle
===================

# Dynamic form fields

The `DynamicFormPropertyBundle` offers helpers for handling dynamic form fields (*properties*).
This can be helpful for several applications where a site admin (not the developer) needs to configure the fields of a 
form, like contact forms, surveys or exams. For example, a user profile manager could use this functionality to
handle definition and management of user profile data.

Example Use cases:
 - Form builder
 - Survey/questionnaire/exam
 - Profile data
 - Contact data
 - Application data
 - etc.

## Getting Started

In order to implement this bundle, the developer must create three entities:

1. A 'container' Entity that holds both:
   1. The dynamic properties (OneToMany)
   2. The response data (OneToMany)
   3. This _may_ implement `DynamicPropertiesContainerInterface`
2. A 'wrapper' Entity that defines the dynamic property.
   1. This must extend `AbstractDynamicPropertyEntity`
3. A PropertyResponse Entity to contain the data responses to the forms.
   1. This must extend `AbstractDynamicPropertyDataEntity`

In a real-world example:
1. `SurveyEntity implements DynamicPropertiesContainerInterface`
   1. OneToMany QuestionEntity
   2. OneToMany SurveyResponseEntity
2. `QuestionEntity extends AbstractDynamicPropertyEntity`
   1. ManyToOne SurveyEntity
3. `SurveyResponseEntity extends AbstractDynamicPropertyDataEntity`
   1. ManyToOne SurveyEntity

After generating the required entities (`symfony console make:entity SurveyEntity` etc...) and adjusting them to 
extend required abstract classes or implement required interfaces, A standard "CRUD" Controller interface can be 
created (`symfony console make:crud SurveyEntity`) to quickly generate much of the needed boilerplate code for a
quick implementation. 

## Form Creation: the 'Building' form

The `Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicFieldCollectionType` formType is a collection of 
Dynamic Fields in your form. You must define the `entry_type` to be your own WrapperEntity (QuestionEntity above).
Each member of the collection provides a form type to define all the needed details of a formType
(a "DynamicFieldSpecification") which consists of two main parts. First a choice field which allows the
selection of a field type using a dropdown list. Upon selection further field-specific form fields for the field options
are loaded using ajax and dynamically added/replaced in the form. 

```php
    $builder
        ->add('questions', DynamicFieldCollectionType::class, [
            'entry_options' => [
                'data_class' => Question::class // required
            ],
        ])
```

## Form Creation: The 'Responding' form

The bundle also provides the `Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\InlineFormDefinitionType` formType.
This provides for inclusion of the dynamic properties of the form. So an application can just use one
form type for adding the defined fields for a given data object form. The formType requires the `dynamicFieldsContainer`
object. This object implements `Zikula\Bundle\DynamicFormPropertyBundle\DynamicPropertiesContainerInterface`. This can
be your 'Container' object (the SurveyEntity above) or possibly another provider like a Respository. The object must
provide a list of dynamic field specifications (as defined by the 'Wrapper' class - The QuestionEntity above). This list
can be optionally sorted or filtered as required.

Example:

```php
    $builder->add('survey', InlineFormDefinitionType::class, [
        'dynamicFieldsContainer' => $survey,
    ]);
```


## Custom FormTypes for your list

You may want to amend, filter or extend the field type list. For example profile modules may want
to add an avatar field type. Similarly, other custom types may be relevant for other applications.
For this purpose you can listen for an event provided by the
`Zikula\Bundle\DynamicFormPropertyBundle\Event\FormTypeChoiceEvent` class.
Implementation inside your listener might look similar to this example:

```php
public function formTypeChoices(FormTypeChoiceEvent $event)
{
    $choices = $event->getChoices();

    $groupName = $this->translator->trans('Other Fields', 'my_trans_domain');
    if (!isset($choices[$groupName])) {
        $choices[$groupName] = [];
    }

    $groupChoices = $choices[$groupName];
    $groupChoices[$this->translator->trans('Avatar')] = AvatarType::class;
    $choices[$groupName] = $groupChoices;

    $event->setChoices($choices);
}
```

### Translation

This bundle provides for "inline" label translation for all dynamic fields. If a label text is not provided, the field
name will be used (like normal text fields). Any _configured_ language/locale will be shown in the dynamic field
creation. 