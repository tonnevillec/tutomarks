<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * SecurityController constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param UserPasswordEncoderInterface $encoder
     * @param MailerInterface $mailer
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->encoder = $encoder;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $this->em->persist($user);
            $this->em->flush();

            $email = (new TemplatedEmail())
                ->from('support@tutomarks.fr')
                ->to($user->getEmail())
                ->subject(ucfirst($this->translator->trans('mail.new_login.subject')) . ' Tutomarks.fr')
                ->htmlTemplate('email/new_login.html.twig')
            ;
            $this->mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'error' => [],
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route("/password_forget", name="app_password")
     * @return Response
     */
    public function passwordForget(): Response
    {
        return $this->render('security/password_forget.html.twig');
    }

    /**
     * @Route("/password_reload", name="app_reloadpwd", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws TransportExceptionInterface
     */
    public function passwordReload(Request $request): Response
    {
        if(!$request->request->has('pwd_email')){
            $this->addFlash('danger', ucfirst($this->translator->trans('password.forget.email.required')));
            return $this->render('security/password_forget.html.twig');
        }

        $email = $request->request->get('pwd_email');
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if(!$user){
            $this->addFlash('danger', ucfirst($this->translator->trans('password.forget.user.unknown')));
            return $this->render('security/password_forget.html.twig');
        }

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $tmpPassword = substr(str_shuffle($permitted_chars), 0, 10);

        $hash = $this->encoder->encodePassword($user, $tmpPassword);
        $user->setPassword($hash);

        $this->em->persist($user);
        $this->em->flush();

        $email = (new TemplatedEmail())
            ->from('support@tutomarks.fr')
            ->to($user->getEmail())
            ->subject(ucfirst($this->translator->trans('mail.passwd.reset.subject')))
            ->htmlTemplate('email/new_login.html.twig')
            ->context([
                'user'          => $user,
                'new_password'  => $tmpPassword
            ])
        ;
        $this->mailer->send($email);

        $this->addFlash('success', ucfirst($this->translator->trans('password.forget.validate')));

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
