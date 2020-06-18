<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.email.placeholder',
                    'name'        => 'email'
                ],
                'label' => $this->translator->trans('user.email.title')
            ])
            ->add('username', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.username.placeholder',
                    'name'        => 'username'
                ],
                'label' => ucfirst($this->translator->trans('user.username.title'))
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder'   => 'user.password.title',
                ],
                'label' => $this->translator->trans('user.password.title')
            ])
            ->add('password_repeat', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.password.repeat'
                ],
                'label' => $this->translator->trans('user.password.repeat')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'            => User::class,
            'translation_domain'    => 'messages'
        ]);
    }
}
