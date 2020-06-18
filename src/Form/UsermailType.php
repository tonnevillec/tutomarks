<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UsermailType extends AbstractType
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
            ->setAction($this->router->generate('user.change.mail'))
            ->add('password_confirm', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.password.repeat'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('user.profil.password')) . ' *',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.profil.new_email.label'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('user.profil.new_email.label')) . ' *',
            ])
            ->add('email_repeat', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user.profil.new_email.label'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => ucfirst($this->translator->trans('user.profil.new_email.validation')) . ' *',
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
