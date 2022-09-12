<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    private UserPasswordHasherInterface $encoder;
    private EntityManagerInterface $em;
    private EmailService $mailer;
    private TranslatorInterface $translator;

    public function __construct(
        UserPasswordHasherInterface $encoder,
        EntityManagerInterface $em,
        EmailService $mailer,
        TranslatorInterface $translator
    ) {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Tu es déjà connecté ;)');

            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): RedirectResponse|Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->encoder->hashPassword($user, $user->getPassword()));
            $this->em->persist($user);
            $this->em->flush();

            $this->mailer->send(
                $user->getEmail(),
                ucfirst($this->translator->trans('mail.new_login.subject')).' Tutomarks.fr',
                'new_login'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.Twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/password', name: 'app_password')]
    public function password(Request $request)
    {
        return $this->render('security/password_forget.html.twig');
    }

    #[Route('/password_reload', name: 'app_reloadpwd', methods: ['POST'])]
    public function passwordReload(Request $request): RedirectResponse
    {
        if (!$request->request->has('pwd_email')) {
            $this->addFlash('danger', ucfirst($this->translator->trans('password.forget.email.required')));

            return $this->redirectToRoute('app_password');
        }

        $email = $request->request->all()['pwd_email'];
        $user = $this->em->getRepository(Users::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $this->addFlash('danger', ucfirst($this->translator->trans('password.forget.user.unknown')));

            return $this->redirectToRoute('app_password');
        }

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $tmpPassword = substr(str_shuffle($permitted_chars), 0, 10);

        $user->setPassword($this->encoder->hashPassword($user, $tmpPassword));
        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->send(
            $user->getEmail(),
            ucfirst($this->translator->trans('mail.passwd.reset.subject')),
            'reset_password',
            [
                'user' => $user,
                'new_password' => $tmpPassword,
            ]
        );

        $this->addFlash('success', ucfirst($this->translator->trans('password.forget.validate')));

        return $this->redirectToRoute('home');
    }
}
