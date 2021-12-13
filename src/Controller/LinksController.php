<?php

namespace App\Controller;

use App\Entity\YoutubeLinks;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/links')]
class LinksController extends AbstractController
{
    private TranslatorInterface $translator;
    private EmailService $mailer;

    public function __construct(TranslatorInterface $translator, EmailService $mailer, ParameterBagInterface $param)
    {
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->param = $param;
    }

    #[Route('/deadlink/{id}', name: 'links.deadlink')]
    public function deadlink(Request $request, YoutubeLinks $links): RedirectResponse
    {
        $this->mailer->send(
            $this->param->get('mailer_from'),
            ucfirst($this->translator->trans('deadlink.mail.subject')),
            'deadlink',
            [
                'mail_from' => $this->getUser()->getEmail(),
                'link' => $links,
            ]
        );

        $this->addFlash('success', ucfirst($this->translator->trans('deadlink.thanks.flash')));

        return $this->redirectToRoute('links.show', [
            'slug' => $links->getSlug(),
            'id' => $links->getId(),
        ]);
    }
}
