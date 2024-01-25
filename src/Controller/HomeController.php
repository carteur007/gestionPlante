<?php

namespace App\Controller;

use App\Entity\Besoin;
use App\Entity\Historique;
use App\Entity\Plante;
use App\Entity\User;
use App\Form\PlanteBesoinType;
use App\Form\PlanteType;
use App\Repository\PlanteRepository;
use App\Service\Notification;
use App\Service\Uploader;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('user/', name: 'app_user_')]
class HomeController extends AbstractController
{
    #[Route('home', name: 'home')]
    public function index(Request $request, PlanteRepository $planteRepository): Response
    {
        $plante = new Plante();
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);
        return $this->render('home/index.html.twig', [
            'plantes' => $planteRepository->findAll(),
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    #[Route('ajouter_plante', name: 'ajouter_plante', methods: ['GET', 'POST'])]
    public function ajouterPlante(Uploader $fileUploader, Request $request, EntityManagerInterface $entityManager): Response
    {
        $plante = new Plante();
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('imagePlante')->getData();
            $user = $this->getUser();
            if ($fichier) {
                $imageName = $fileUploader->upload($fichier);
                $plante->setImagePlante($imageName);
            }
            $plante->setUser($user);
            $entityManager->persist($plante);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_ajouter_plante', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/plante/ajouter.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    #[Route('plante/{id}/edit', name: 'editer_plante', methods: ['GET', 'POST'])]
    public function definirBesoin(Notification $notifier, Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        $besoin = new Besoin();
        $form = $this->createForm(PlanteBesoinType::class, $plante);
        $form->handleRequest($request);
        $user = $this->getUser();

        //validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            //Definition des besoins en eau de la plante
            $besoin->setQuantiteEau($form->get('quantiteEau')->getData());
            $besoin->setFrecArrosage($form->get('frecArrosage')->getData());
            $besoin->setPlante($plante);
            $entityManager->persist($besoin);
            $entityManager->flush(); 

            //Notification de l'utilisateur
            $subject = 'Rappel d\'arrosage de la plante';
            $message = 'Vous devez arroser votre plane(' . $plante->getNom() . ') avec ' . $besoin->getQuantiteEau() . 'L d\'eau, tous les ' . $besoin->getFrecArrosage() . ' jours';
            $this->_notify(
                $user,
                $subject,
                $message,
                $notifier,
                'besoin',
                $besoin
            );
            return $this->redirectToRoute('app_user_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/plante/ajouter.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }
    #[Route('plante/{id}/arrosage', name: 'arrosage_plante', methods: ['GET', 'POST'])]
    public function arrosagePlante(Notification $notifier, Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        return $this->render('home/plante/arrosage.html.twig', [
            'user' => $user,
            'plante' => $plante,
            'besoins' => $plante->getBesoins(),
            'historiques' => $plante->getHistoriques(),
        ]);
    }
    #[Route('plante/p/{id}/q/{q}/f/{f}', name: 'arrosage_besoin_plante', methods: ['GET', 'POST'])]
    public function marquagePlante(Notification $notifier, EntityManagerInterface $entityManager, Plante $plante,int $q,int $f): JsonResponse
    {
        $user = $plante->getUser();
        $besoin = new Besoin();

        //Definition des besoins en eau de la plante
        $besoin->setQuantiteEau($q);
        $besoin->setFrecArrosage($f);
        $besoin->setPlante($plante);

        //Marquage de l'arrosage de la plante
        $plante->setIsArrosed(true);
        $entityManager->persist($besoin);

        //Gestion de l'historique d'arrosage
        $historique = new Historique();
        $description = $besoin->getQuantiteEau() . 'L d\'eau, tous les ' . $besoin->getFrecArrosage() . ' jours';
        $historique->setPlante($plante);
        $historique->setDateArrosage(new DateTimeImmutable());
        $historique->setDescription($description);
        $entityManager->persist($historique);

        $entityManager->flush(); 

        //Notification de l'utilisateur
        $subject = 'Rappel d\'arrosage de la plante';
        $message = 'Vous devez arroser la plante(' . $plante->getNom() . ') avec ' . $q . 'L d\'eau, tous les ' . $f . ' jours';
        $this->_notify(
            $user,
            $subject,
            $message,
            $notifier,
            'besoin',
            $besoin
        );
        return new JsonResponse(
            ["message" => 'Besoins ajouté avec success!'],
            201,
            ["Content-type" => "application/json"]
        );
    }

    /**
     * 
     */
    public function _notify(
        User $user,
        String $subject,
        String $message,
        Notification $mailService,
        String $template,
        Besoin $besoin
    ): void {
        $mailService->notify(
            'no-reply@GSP.com',
            $user->getEmail(),
            $subject,
            $template,
            compact('besoin','message')
        );
    }
}
