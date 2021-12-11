<?php

namespace App\Form;

use App\Entity\YoutubeLinks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class YoutubeLinksType extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', null, [
                'label'     => ucfirst($this->translator->trans('tutos.addurl.label')),
                'required'  => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => YoutubeLinks::class,
            'method'            => 'post',
            'csrf_protection'   => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
