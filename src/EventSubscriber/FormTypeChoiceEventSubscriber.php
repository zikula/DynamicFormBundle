<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\Bundle\DynamicFormBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent;
use Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceTypeTransformed;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceWithOtherType;

class FormTypeChoiceEventSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormTypeChoiceEvent::class => ['addDefaultChoices', 1000],
        ];
    }

    public function addDefaultChoices(FormTypeChoiceEvent $event): void
    {
        $defaultChoices = new FormTypesChoices([
            $this->trans('Text fields') => [
                $this->trans('Text') => TextType::class,
                $this->trans('Textarea') => TextareaType::class,
                $this->trans('Email') => EmailType::class,
                $this->trans('Integer') => IntegerType::class,
                $this->trans('Money') => MoneyType::class,
                $this->trans('Number') => NumberType::class,
                $this->trans('Password') => PasswordType::class,
                $this->trans('Percent') => PercentType::class,
                $this->trans('Url') => UrlType::class,
                $this->trans('Range') => RangeType::class,
                $this->trans('Phone number') => TelType::class,
            ],
            $this->trans('Choice fields') => [
                $this->trans('Choice (standard)') => ChoiceType::class,
                $this->trans('Choice transformed') => ChoiceTypeTransformed::class,
                $this->trans('Choice with other') => ChoiceWithOtherType::class,
                $this->trans('Checkbox') => CheckboxType::class,
                $this->trans('Country') => CountryType::class,
                $this->trans('Language') => LanguageType::class,
                $this->trans('Locale') => LocaleType::class,
                $this->trans('Timezone') => TimezoneType::class,
                $this->trans('Currency') => CurrencyType::class,
            ],
            $this->trans('Date and time fields') => [
                $this->trans('Date') => DateType::class,
                $this->trans('DateTime') => DateTimeType::class,
                $this->trans('Time') => TimeType::class,
                $this->trans('Birthday') => BirthdayType::class,
                $this->trans('Week number') => WeekType::class,
            ],
            $this->trans('Other fields') => [
                $this->trans('File') => FileType::class,
            ],
        ]);

        $event->setChoices($defaultChoices);
    }

    private function trans(string $id): string
    {
        return $this->translator->trans($id);
    }
}
