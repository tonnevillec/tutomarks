<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Langues;
use App\Entity\Tags;
use App\Entity\Tutos;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TutosType extends AbstractType
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
            ->add('title', null, [
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('tutos.title.label')) . ' *',
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('tutos.title.placeholder'))
                ],
                'required'  => true
            ])
            ->add('creator', null, [
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('tutos.creator.label')) . ' *',
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('tutos.creator.placeholder'))
                ],
                'required'  => true
            ])
            ->add('url', null, [
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('tutos.url.label')) . ' *',
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('tutos.url.placeholder'))
                ],
                'required'  => true
            ])
            ->add('description', TextareaType::class, [
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label'         => ucfirst($this->translator->trans('tutos.description.label')),
                'attr'  => [
                    'class'     => 'mh-250p'
                ],
                'required'      => false
            ])
            ->add('tags', EntityType::class, [
                'class'         => Tags::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
                },
                'label' => ucfirst($this->translator->trans('tutos.tags.label')) . ' *',
                'choice_value'  => 'id',
                'multiple'      => true,
//                'attr' => [
//                    'class'     => 'select2'
//                ]
                'required'      => false,
                'choice_label'  => 'title',
                'expanded'      => true
            ])
            ->add('category', EntityType::class, [
                'class'         => Categories::class,
                'label' => ucfirst($this->translator->trans('tutos.category.label')) . ' *',
                'choice_value'  => 'title',
                'multiple'      => false,
                'required'      => true
            ])
            ->add('langue', EntityType::class, [
                'class'         => Langues::class,
                'label' => ucfirst($this->translator->trans('tutos.langue.label')) . ' *',
                'choice_value'  => 'name',
                'multiple'      => false,
                'required'      => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tutos::class,
        ]);
    }
}
