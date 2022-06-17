DynamicFormBundle
=================

![Build Status](https://img.shields.io/github/workflow/status/zikula/DynamicFormBundle/Symfony)
[![codecov](https://codecov.io/gh/zikula/DynamicFormBundle/branch/main/graph/badge.svg?token=9BIL3EQ5IK)](https://codecov.io/gh/zikula/DynamicFormBundle)
![License](https://img.shields.io/github/license/zikula/DynamicFormBundle)

The `DynamicFormBundle` offers helpers for handling dynamic form fields (forms built from definitions stored in
the database). This can be helpful for several applications where a site admin (not the developer) needs to configure
the fields of a form, like contact forms, surveys or exams, employment applications, etc.

#### [An example SurveyMaker application is available](https://github.com/zikula/DynamicFormBundleExample).

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require zikula/dynamic-form-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require zikula/dynamic-form-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Zikula\Bundle\DynamicFormBundle\ZikulaDynamicFormBundle::class => ['all' => true],
];
```

Getting Started
---------------

In order to implement this bundle, the developer must create three entities:

1. A 'container' Entity that holds both:
   1. The form specifications (OneToMany)
   2. The response data (OneToMany)
   3. This _may_ extend `AbstractSpecificationContainer`
2. A 'wrapper' Entity that defines the form specification.
   1. This **must** extend `AbstractFormSpecification`
3. A 'response' Entity to contain the data responses to the forms.
   1. This **must** extend `AbstractResponseData`

Form Creation: the 'Building' form
----------------------------------

The `Zikula\Bundle\DynamicFormBundle\Form\Type\FormSpecificationCollectionType` formType is a collection of Form
Specifications in your form. You must define the `entry_type` to be your own 'wrapper' Entity (item 2 above). Each
member of the collection provides a form type to define all the needed details of a formType (a `FormSpecification`).
Form options are loaded using ajax and dynamically added/replaced in the form.

```php
    $builder
        ->add('questions', FormSpecificationCollectionType::class, [
            'entry_options' => [
                'data_class' => Question::class // required
            ],
        ])
```
**IMPORTANT NOTE**: The Javascript for these actions is automatically loaded. However, the javascript is jQuery-based.
Therefore, **you must include jQuery in your front-end assets.**

Form Creation: The 'Responding' form
------------------------------------

The bundle also provides the `Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicFieldsType` formType. This
provides for inclusion of the defined dynamic fields. The formType requires the `specificationContainer` object.
This object implements `Zikula\Bundle\DynamicFormBundle\Container\SpecificationContainerInterface`. This can be your
'Container' object (item 1 above) or possibly another provider like a Repository. The object must provide a list of form
specifications (as defined by the 'wrapper' class). This list can be optionally sorted or filtered as required.

Example:

```php
    $builder->add('survey', DynamicFieldsType::class, [
        'specificationContainer' => $myContainer,
    ]);
```

### More information

 - see [Additional Documentation](docs/index.md)
 - This bundle suggests [scienta/doctrine-json-functions](https://github.com/ScientaNL/DoctrineJsonFunctions)
   - You must configure the DoctrineJsonFunctions bundle at the application level. It is not done automatically.