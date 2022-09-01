<?php

namespace App\Form;

use App\Entity\Hebdoo;
use App\Entity\Languages;
use App\Entity\Tags;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class HebdooType extends AbstractType
{
    public function __construct(
        protected readonly TranslatorInterface $translator,
        private readonly Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => ucfirst($this->translator->trans('hebdoo.title.label')).' *',
                'attr' => [
                    'placeholder' => ucfirst($this->translator->trans('hebdoo.title.placeholder')),
                ],
                'required' => true,
            ])
            ->add('url', null, [
                'label' => $this->translator->trans('hebdoo.url.label').' *',
                'attr' => [
                    'placeholder' => $this->translator->trans('hebdoo.url.placeholder'),
                ],
                'required' => true,
            ])
            ->add('pseudo', null, [
                'label' => ucfirst($this->translator->trans('hebdoo.pseudo.label')).' *',
                'attr' => [
                    'placeholder' => ucfirst($this->translator->trans('hebdoo.pseudo.placeholder')),
                    'value' => $this->security->getUser() ? $this->security->getUser()->getUsername() : null,
                ],
                'required' => true,
            ])

            ->add('tags', EntityType::class, [
                'class' => Tags::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
                },
                'label' => ucfirst($this->translator->trans('hebdoo.tags.label')),
                'choice_value' => 'id',
                'multiple' => true,
                'required' => false,
                'choice_label' => 'title',
                'expanded' => false,
            ])

            ->add('language', EntityType::class, [
                'class' => Languages::class,
                'label' => ucfirst($this->translator->trans('hebdoo.language.label')),
                'choice_value' => 'name',
                'multiple' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebdoo::class,
        ]);
    }
}
