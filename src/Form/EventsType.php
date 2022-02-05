<?php

namespace App\Form;

use App\Entity\Authors;
use App\Entity\Events;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventsType extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label_attr' => [
                    'class' => 'label',
                ],
                'label' => ucfirst($this->translator->trans('events.title.label')) . ' *',
                'attr' => [
                    'placeholder' => ucfirst($this->translator->trans('events.title.placeholder')),
                ],
                'required' => true,
            ])
            ->add('url', null, [
                'label_attr' => [
                    'class' => 'label',
                ],
                'label' => ucfirst($this->translator->trans('events.url.label')) . ' *',
                'attr'  => [
                    'placeholder' => ucfirst($this->translator->trans('events.url.placeholder')),
                ],
                'required' => true,
            ])
            ->add('author', EntityType::class, [
                'label' => ucfirst($this->translator->trans('slinks.authors.label')),
                'class' => Authors::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.title', 'ASC');
                },
            ])
            ->add('started_at', DateType::class, [
                'widget'    => 'single_text',
                'label'     => ucfirst($this->translator->trans('events.started_at.label'))
            ])
            ->add('live_on_twitch', CheckboxType::class, [
                'label'         => ucfirst($this->translator->trans('events.live_on_twitch.label')),
                'data'          => false,
                'required'      => false,
                'label_attr'    => [
                    'class' => 'checkbox-switch'
                ]
            ])
            ->add('live_on_twitter', CheckboxType::class, [
                'label'         => ucfirst($this->translator->trans('events.live_on_twitter.label')),
                'data'          => false,
                'required'      => false,
                'label_attr'    => [
                    'class' => 'checkbox-switch'
                ]
            ])
            ->add('live_on_youtube', CheckboxType::class, [
                'label'         => ucfirst($this->translator->trans('events.live_on_youtube.label')),
                'data'          => false,
                'required'      => false,
                'label_attr'    => [
                    'class' => 'checkbox-switch'
                ]
            ])
            ->add('is_physical', CheckboxType::class, [
                'label'         => ucfirst($this->translator->trans('events.is_physical.label')),
                'data'          => true,
                'required'      => false,
                'label_attr'    => [
                    'class' => 'checkbox-switch'
                ]
            ])
            ->add('is_free', CheckboxType::class, [
                'label'         => ucfirst($this->translator->trans('events.is_free.label')),
                'data'          => true,
                'required'      => false,
                'label_attr'    => [
                    'class' => 'checkbox-switch'
                ]
            ])
            ->add('description', null, [
                'label'     => ucfirst($this->translator->trans('events.description.label')),
                'required'  => false,
                'attr'      => [
                    'class' => 'mb-3',
                    'rows'  => 10
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}