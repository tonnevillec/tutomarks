<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Channels;
use App\Entity\Langues;
use App\Entity\Levels;
use App\Entity\Prices;
use App\Entity\Tags;
use App\Entity\TutoSearch;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TutoSearchType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', null, [
                'label'     => false,
                'required'  => false,
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('search.field.placeholder')) . ' ...'
                ]
            ])
            ->add('category', EntityType::class, [
                'label'         => ucfirst($this->translator->trans('search.category.label')),
                'required'      => false,
                'class'         => Categories::class,
                'choice_value'  => 'id',
                'choice_label'  => 'title',
                'multiple'      => false,
            ])
            ->add('tags', EntityType::class, [
                'label'         => ucfirst($this->translator->trans('search.tags.label')),
                'required'      => false,
                'class'         => Tags::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC')
                    ;
                },
                'choice_value'  => 'id',
                'choice_label'  => 'title',
                'multiple'      => true,
                'expanded'      => true
            ])
            ->add('channel', EntityType::class, [
                'label'         => ucfirst($this->translator->trans('search.creator.label')),
                'class'         => Channels::class,
                'required'      => false,
                'choice_value'  => 'id',
                'choice_label'  => 'title',
                'multiple'      => false,
            ])
            ->add('evaluation', NumberType::class, [
                'label'         => ucfirst($this->translator->trans('search.evaluation.label')),
                'required'      => false,
            ])
            ->add('langue', EntityType::class, [
                'label'         => ucfirst($this->translator->trans('search.langue.label')),
                'required'      => false,
                'class'         => Langues::class,
                'choice_value'  => 'id',
                'choice_label'  => 'name',
                'multiple'      => false,
            ])
            ->add('price', EntityType::class, [
                'label'         => ucfirst($this->translator->trans('search.price.label')),
                'required'      => false,
                'class'         => Prices::class,
                'choice_value'  => 'id',
                'choice_label'  => 'name',
                'multiple'      => false,
            ])
            ->add('minlevel', EntityType::class, [
                'label'         => ucfirst($this->translator->trans('search.minlevel.label')),
                'required'      => false,
                'class'         => Levels::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.rank', 'ASC');
                },
                'choice_label'  => 'name',
                'multiple'      => false,
            ])
            ->add('pined', ChoiceType::class, [
                'label'     => ucfirst($this->translator->trans('search.pined.label')),
                'required'  => true,
                'choices'   => [
                    ucfirst($this->translator->trans('search.pined.all'))   => null,
                    ucfirst($this->translator->trans('search.pined.oui'))   => true,
                    ucfirst($this->translator->trans('search.pined.non'))   => false,
                ],
                'data'      => null,
                'multiple'  => false,
                'expanded'  => true,
                'attr'  => [
                    'class' => 'form-check-inline'
                ]
            ])
            ->add('shown', ChoiceType::class, [
                'label'     => ucfirst($this->translator->trans('search.shown.label')),
                'required'  => true,
                'choices'   => [
                    ucfirst($this->translator->trans('search.shown.all'))   => null,
                    ucfirst($this->translator->trans('search.shown.oui'))   => true,
                    ucfirst($this->translator->trans('search.shown.non'))   => false,
                ],
                'data'      => null,
                'multiple'  => false,
                'expanded'  => true,
                'attr'  => [
                    'class' => 'form-check-inline'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => TutoSearch::class,
            'method'            => 'get',
            'csrf_protection'   => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
