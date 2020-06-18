<?php

namespace App\Controller;

use App\Form\UsermailType;
use App\Form\UserpasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * UserController constructor.
     * @param TranslatorInterface $translator
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     */
    public function __construct(TranslatorInterface $translator, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->translator = $translator;
        $this->encoder = $encoder;
        $this->em = $em;
    }

    /**
     * @Route("/profil", name="user.profil")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form_mail = $this->createForm(UsermailType::class, $this->getUser());
        $form_pass = $this->createForm(UserpasswordType::class, $this->getUser());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->getUser());
            $em->flush();

            $this->addFlash('success', $this->translator->trans('user.profil.validate_update'));
            return $this->redirectToRoute('user.profil');
        }

        return $this->render('user/profil.html.twig', [
            'form'          => $form->createView(),
            'form_email'    => $form_mail->createView(),
            'form_password' => $form_pass->createView(),
        ]);
    }

    /**
     * @Route("/change/mail", name="user.change.mail", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function changeMail(Request $request):JsonResponse
    {
        $form_mail = $this->createForm(UsermailType::class, $this->getUser());
        $form_mail->handleRequest($request);

        $message = '';
        $code = 200;
        $new = '';

        if($form_mail->isSubmitted() && $form_mail->isValid()) {
            if($this->encoder->isPasswordValid($this->getUser(), $form_mail->getViewData()->getPasswordConfirm())){
                $user = $this->getUser();
                $new = $form_mail->getViewData()->getEmail();
                $user->setEmail($new);

                $this->em->persist($user);
                $this->em->flush();
                $message = ucfirst($this->translator->trans('user.profil.new_email.validate_update'));
            } else {
                $message = ucfirst($this->translator->trans('user.profil.new_email.fail_update'));
                $code = 400;
            }
        } else {
            $message = $form_mail->getErrors(true)[0]->getMessage();
            $code = 400;
        }

        return $this->json([
            'message'   => $message,
            'newValue'  => $new,
            'code'      => $code,
        ], $code);
    }


    /**
     * @Route("/change/password", name="user.change.password", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request):JsonResponse
    {
        $message = '';
        $code = 200;
        $new = '';

        $old = $request->request->get('userpassword')['password_confirm'];
        if($this->encoder->isPasswordValid($this->getUser(), $old)){
            $form = $this->createForm(UserpasswordType::class, $this->getUser());
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $user = $this->getUser();
                $new = $form->getViewData()->getPassword();

                $hash = $this->encoder->encodePassword($user, $new);
                $user->setPassword($hash);

                $this->em->persist($user);
                $this->em->flush();
                $message = ucfirst($this->translator->trans('user.profil.new_password.validate_update'));
            } else {
                $message = $form->getErrors(true)[0]->getMessage();
                $code = 400;
            }
        } else {
            $message = ucfirst($this->translator->trans('user.profil.new_password.fail_update'));
            $code = 400;
        }

        return $this->json([
            'message'   => $message,
            'newValue'  => $new,
            'code'      => $code
        ], $code);
    }

    /**
     * @Route("/delete/account", name="user.delete.account", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAccount(Request $request):JsonResponse
    {
        $message = '';
        $code = 200;
        $new = '';

        $old = $request->request->get('mdp');
        if($this->encoder->isPasswordValid($this->getUser(), $old)){
            $this->getUser()->setIsActif(false);
            $this->em->persist($this->getUser());
            $this->em->flush();
            $message = ucfirst($this->translator->trans('user.profil.delete.validate_update'));

        } else {
            $message = ucfirst($this->translator->trans('user.profil.delete.fail_update'));
            $code = 400;
        }

        return $this->json([
            'message'   => $message,
            'newValue'  => $new,
            'code'      => $code
        ], $code);
    }
}
