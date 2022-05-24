DynamicFormPropertyBundle
=========================

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require zikula/dynamic-form-property-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require zikula/dynamic-form-property-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Zikula\Bundle\DynamicFormPropertyBundle\ZikulaDynamicFormPropertyBundle::class => ['all' => true],
];
```

Dynamic form fields
===================

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

Getting Started
---------------

In order to implement this bundle, the developer must create three entities:

1. A 'container' Entity that holds both:
   1. The dynamic property specifications (OneToMany)
   2. The response data (OneToMany)
   3. This _may_ implement `DynamicPropertiesContainerInterface`
2. A 'wrapper' Entity that defines the dynamic property specification.
   1. This must extend `AbstractDynamicPropertySpecification`
3. A PropertyResponse Entity to contain the data responses to the forms.
   1. This must extend `AbstractDynamicPropertyData`

In a real-world example:
1. `Survey implements DynamicPropertiesContainerInterface`
   1. OneToMany Question
   2. OneToMany SurveyResponse
2. `Question extends AbstractDynamicPropertySpecification`
   1. ManyToOne Survey
3. `SurveyResponse extends AbstractDynamicPropertyData`
   1. ManyToOne Survey

After generating the required entities (`symfony console make:entity Survey` etc...) and adjusting them to 
extend required abstract classes or implement required interfaces, A standard "CRUD" Controller can be 
created (`symfony console make:crud Survey`) to quickly generate much of the needed boilerplate code for a
sample implementation. 

Form Creation: the 'Building' form
----------------------------------

The `Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicFieldCollectionType` formType is a collection of 
Dynamic Fields in your form. You must define the `entry_type` to be your own 'wrapper' Entity (`Question` above).
Each member of the collection provides a form type to define all the needed details of a formType
(a `DynamicPropertySpecification`) which consists of two main parts. First a choice field which allows the
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
**IMPORTANT NOTE**: The Javascript for these actions is automatically loaded. However, the javascript is jQuery-based.
Therefore, **you must include jQuery in your front-end assets.**

Form Creation: The 'Responding' form
------------------------------------

The bundle also provides the `Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\InlineFormDefinitionType` formType.
This provides for inclusion of the dynamic properties of the form. So an application can just use one
form type for adding the defined fields for a given data object form. The formType requires the `dynamicFieldsContainer`
object. This object implements `Zikula\Bundle\DynamicFormPropertyBundle\DynamicPropertiesContainerInterface`. This can
be your 'Container' object (the `Survey` above) or possibly another provider like a Repository. The object must
provide a list of dynamic property specifications (as defined by the 'Wrapper' class - The `Question` above). This list
can be optionally sorted or filtered as required.

Example:

```php
    $builder->add('survey', InlineFormDefinitionType::class, [
        'dynamicFieldsContainer' => $survey,
    ]);
```

### More information

 - see [Additional Documentation](docs/index.md)
 - This bundle suggests [scienta/doctrine-json-functions](https://github.com/ScientaNL/DoctrineJsonFunctions)
   - You must configure the DoctrineJsonFunctions bundle at the application level. It is not done automatically.