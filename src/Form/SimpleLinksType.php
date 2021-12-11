<?php

namespace App\Form;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Languages;
use App\Entity\SimpleLinks;
use App\Entity\Tags;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SimpleLinksType extends AbstractType
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
                'label_attr'    => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('tutos.title.label')) . ' *',
                'attr'  => [
                    'placeholder'   => ucfirst($this->translator->trans('tutos.title.placeholder'))
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
                'label' => ucfirst($this->translator->trans('tutos.tags.label')) ,
                'choice_value'  => 'id',
                'multiple'      => true,
                'required'      => false,
                'choice_label'  => 'title',
                'expanded'      => true
            ])
            ->add('category', EntityType::class, [
                'class'         => Categories::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.is_actif = true')
                        ->andWhere('c.link_entity <> :youtube')
                        ->setParameter('youtube', 'youtube')
                        ->orderBy('c.title', 'ASC');
                },
                'label' => ucfirst($this->translator->trans('tutos.category.label')) . ' *',
                'choice_value'  => 'title',
                'multiple'      => false,
                'required'      => true
            ])
            ->add('language', EntityType::class, [
                'class'         => Languages::class,
                'label' => ucfirst($this->translator->trans('tutos.langue.label')) . ' *',
                'choice_value'  => 'name',
                'multiple'      => false,
                'required'      => true
            ])

            ->add('author', EntityType::class, [
                'label'         => ucfirst($this->translator->trans('slinks.authors.label')),
                'class'         => Authors::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.title', 'ASC');
                },
            ])
            ->add('is_publish', null, [
                'label'         => ucfirst($this->translator->trans('links.is_publish.label')),
                'data'          => true
            ])
            ->add('imageFile', FileType::class, [
                'label'         => ucfirst($this->translator->trans('links.image.label')),
                'required'      => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimpleLinks::class,
        ]);
    }
}
