<?php

namespace App\Form;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Languages;
use App\Entity\LinkSearch;
use App\Entity\Tags;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class LinkSearchType extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', null, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => ucfirst($this->translator->trans('search.field.placeholder')).' ...',
                ],
//                'help'      => ucfirst($this->translator->trans('search.field.help'))
            ])
            ->add('page', HiddenType::class, [
                'data' => '1',
            ])
            ->add('categories', EntityType::class, [
                'label' => ucfirst($this->translator->trans('search.category.label')),
                'required' => false,
                'class' => Categories::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.is_actif = 1')
                        ->orderBy('c.title', 'ASC')
                        ;
                },
                'choice_value' => 'id',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('tags', EntityType::class, [
                'label' => ucfirst($this->translator->trans('search.tags.label')),
                'required' => false,
                'class' => Tags::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC')
                        ;
                },
                'choice_value' => 'id',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'mb-2',
                ],
            ])
            ->add('languages', EntityType::class, [
                'label' => ucfirst($this->translator->trans('search.langue.label')),
                'required' => false,
                'class' => Languages::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.name', 'ASC')
                        ;
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('authors', EntityType::class, [
                'label' => ucfirst($this->translator->trans('search.authors.label')),
                'required' => false,
                'class' => Authors::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.title', 'ASC')
                        ;
                },
                'choice_value' => 'id',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'mb-2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LinkSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
