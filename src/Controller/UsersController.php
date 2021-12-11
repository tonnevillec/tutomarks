<?php

namespace App\Controller;

use App\Entity\Links;
use App\Entity\SimpleLinks;
use App\Form\UsermailType;
use App\Form\UserpasswordType;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use App\Repository\YoutubeLinksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route("/users")]
class UsersController extends AbstractController
{
    private TranslatorInterface $translator;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $encoder;
    private UsersRepository $usersRepository;
    private YoutubeLinksRepository $youtubeLinksRepository;

    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $encoder,
        UsersRepository $usersRepository,
        YoutubeLinksRepository $youtubeLinksRepository
    )
    {
        $this->translator = $translator;
        $this->em = $em;
        $this->encoder = $encoder;
        $this->usersRepository = $usersRepository;
        $this->youtubeLinksRepository = $youtubeLinksRepository;
    }

    #[Route("/profil", name: "users.profil")]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UsersType::class, $this->getUser());
        $form_mail = $this->createForm(UsermailType::class, $this->getUser());
        $form_pass = $this->createForm(UserpasswordType::class, $this->getUser());

        $form->handleRequest($request);

        $action = $request->query->has('action') ? $request->query->get('action') : '';
        $code = $request->query->has('code') ? $request->query->get('code') : '';

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($this->getUser());
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('user.profil.validate_update'));
        }

        return $this->render('users/profil.html.twig', [
            'action'        => $action,
            'code'          => $code,
            'form'          => $form->createView(),
            'form_email'    => $form_mail->createView(),
            'form_password' => $form_pass->createView(),
        ]);
    }

    #[Route("/change/password", name: "users.change.password", methods: ['POST'])]
    public function changePassword(Request $request):RedirectResponse
    {
        $message = '';
        $code = 'danger';
        $action = 'password';

        $old = $request->request->get('userpassword')['password_confirm'];
        if($this->encoder->isPasswordValid($this->getUser(), $old)){
            $form = $this->createForm(UserpasswordType::class, $this->getUser());
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $user = $this->getUser();
                $new = $form->getViewData()->getPassword();

                $hash = $this->encoder->hashPassword($this->getUser(), $new);
                $user->setPassword($hash);

                $this->em->persist($user);
                $this->em->flush();

                $message = ucfirst($this->translator->trans('user.profil.new_password.validate_update'));
                $code = 'success';
            } else {
                $message = $form->getErrors(true)[0]->getMessage();
            }
        } else {
            $message = ucfirst($this->translator->trans('user.profil.new_password.fail_update'));
        }

        $this->addFlash($code, $message);
        return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
    }

    #[Route("/change/mail", name: "users.change.mail", methods: ['POST'])]
    public function changeMail(Request $request):RedirectResponse
    {
        $code = 'danger';
        $user = $this->usersRepository->findOneBy(['email' => $this->getUser()->getEmail(), 'is_actif' => true]);
        $datas = $request->request->all()['usermail'];
        $action = 'email';

        if (null === $user) {
            $message = ucfirst($this->translator->trans('user.profil.undefined'));
            $this->addFlash($code, $message);

            return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
        }

        // Controle MOT DE PASSE OK
        if(!$this->encoder->isPasswordValid($user, $datas['password_confirm'])) {
            $this->addFlash($code, 'Mot de passe invalide');

            return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
        }

        // Controle Email1 = Email2
        if($datas['email'] !== $datas['email_repeat']) {
            $this->addFlash($code, 'Les deux mails saisis sont différents');

            return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
        }

        // Controle unicité de l'addresse email dans la base
        if($user->getEmail() === $datas['email']) {
            $code = 'info';
            $this->addFlash($code, 'Le mail saisi est identique à votre mail actuel');

            return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
        }

        $uniq = $this->usersRepository->findBy(['email' => $datas['email'], 'is_actif' => true]);
        if(count($uniq) !== 0) {
            $this->addFlash($code, 'Un utilisateur existe déjà avec cette adresse mail');

            return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
        }

        // Soumission du formulaire pour modif
        $form_mail = $this->createForm(UsermailType::class, $user);
        $form_mail->handleRequest($request);

        if($form_mail->isSubmitted() && $form_mail->isValid()) {
            $new = $form_mail->getViewData()->getEmail();
            $user->setEmail($new);

            $this->em->persist($user);
            $this->em->flush();

            $message = ucfirst($this->translator->trans('user.profil.new_email.validate_update'));
            $code = 'success';
        } else {
            $message = $form_mail->getErrors(true)[0]->getMessage();
        }

        $this->addFlash($code, $message);
        return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
    }

    #[Route("/delete/account", name: "users.delete", methods: ['POST'])]
    public function deleteAccount(Request $request):RedirectResponse
    {
        $code = 'danger';
        $user = $this->usersRepository->findOneBy(['email' => $this->getUser()->getEmail(), 'is_actif' => true]);
        $datas = $request->request->all()['userdelete'];
        $action = 'delete';

        if (null === $user) {
            $message = ucfirst($this->translator->trans('user.profil.undefined'));
            $this->addFlash($code, $message);

            return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
        }

        if($datas['linked'] === 'no') {
            if(!$this->encoder->isPasswordValid($user, $datas['password_confirm'])) {
                $this->addFlash($code, 'Mot de passe invalide');

                return $this->redirectToRoute('users.profil', ['action' => $action, 'code' => $code]);
            }
        }

        $user->setIsActif(false);
        $this->em->persist($user);
        $this->em->flush();

        return $this->redirectToRoute('app_logout');
    }

    #[Route("/my_links", name: "users.my_links")]
    public function myLinks(Request $request): Response
    {
        $ytlinks = $this->youtubeLinksRepository->findForMe($this->getUser());
        $slinks = $this->em->getRepository(SimpleLinks::class)
            ->findBy([
                'published_by' => $this->getUser()->getId()
            ], [
                'published_at' => 'DESC'
            ]);

        return $this->render('users/my_links.html.twig', [
            'ytlinks'   => $ytlinks,
            'slinks'    => $slinks
        ]);
    }

    #[Route("/publish_links", name: "users.publish_links", methods: ['POST'])]
    public function publishLink(Request $request)
    {
        $datas = $request->request->get('link');
        $link = $this->em->getRepository(Links::class)->find($datas);

        if(!$link){
            $this->addFlash('danger', "Le lien n'existe pas");

            return $this->redirectToRoute('home');
        }

        if($link->getPublishedBy()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', "Vous n'êtes pas autorisé à modifier ce lien");

            return $this->redirectToRoute('home');
        }

        $link->setIsPublish(!$link->getIsPublish());
        $this->em->persist($link);
        $this->em->flush();

        $this->addFlash('success', "Modification correctement effectuée");

        return $this->redirectToRoute('users.my_links');
    }
}
