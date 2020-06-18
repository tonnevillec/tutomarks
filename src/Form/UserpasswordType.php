<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserpasswordType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->router->generate('user.change.password'))
            ->add('password_confirm', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.profil.new_password.confirm'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('user.profil.new_password.confirm')) . ' *',
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.profil.new_password.label'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('user.profil.new_password.label')) . ' *',
            ])
            ->add('password_repeat', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.password.repeat'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('user.password.repeat')) . ' *',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
