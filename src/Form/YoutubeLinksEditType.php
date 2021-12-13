<?php

namespace App\Form;

use App\Entity\Languages;
use App\Entity\Tags;
use App\Entity\YoutubeLinks;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class YoutubeLinksEditType extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_publish', CheckboxType::class, [
                'label' => ucfirst($this->translator->trans('ytlinks.is_publish.label')),
                'data' => true,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tags::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
                },
                'label' => ucfirst($this->translator->trans('ytlinks.tags.label')),
                'choice_value' => 'id',
                'multiple' => true,
                'required' => false,
                'choice_label' => 'title',
                'expanded' => true,
            ])
            ->add('language', EntityType::class, [
                'class' => Languages::class,
                'label' => ucfirst($this->translator->trans('ytlinks.langue.label')).' *',
                'choice_value' => 'name',
                'multiple' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => YoutubeLinks::class,
            'method' => 'post',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
