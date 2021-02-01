<?php

namespace App\Form;

use App\Entity\Channels;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChannelsType extends AbstractType
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('channel.title.label')) . ' *',
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('channel.title.placeholder'))
                ],
                'required'  => true
            ])
//            ->add('description')
//            ->add('thumbnails_url')
            ->add('site_url', UrlType::class, [
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('channel.site_url.label')),
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('channel.site_url.placeholder'))
                ],
                'required'  => false
            ])
            ->add('youtube_id', null, [
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('channel.youtube_id.label')),
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('channel.youtube_id.placeholder'))
                ],
                'required'  => false
            ])
//            ->add('youtube_custom_url')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Channels::class,
        ]);
    }
}
