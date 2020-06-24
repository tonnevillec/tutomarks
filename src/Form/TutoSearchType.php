<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Langues;
use App\Entity\Levels;
use App\Entity\Prices;
use App\Entity\Tags;
use App\Entity\TutoSearch;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'choice_value'  => 'id',
                'choice_label'  => 'title',
                'multiple'      => true,
                'expanded'      => true
            ])
            ->add('creator', null, [
                'label'         => ucfirst($this->translator->trans('search.creator.label')),
                'required'      => false
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
