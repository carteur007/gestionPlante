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

/**
 * Controller Global
 * 
 * @author Saatsa franklin Blerio <saatsafranklin@gmail.com>
 * @description Analyste programmeur/developpeur full-strack
 *
 */
#[Route('/user', name: 'app_user_')]
class HomeController extends AbstractController
{
    /**
     * Action de la page d'accueil
     * 
     * @param mixed|Request $request,
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $plante = new Plante();
        $user = $this->getUser();
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);
        
        return $this->render('home/index.html.twig', [
            'plantes' => $user->getPlantes(),
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    /**
     * Action de la page d'ajout d'une plante
     * 
     * @param mixed|Uploader $fileUploader,
     * @param mixed|Request $request
     * @param mixed|EntityManagerInterface $entityManager,
     * @return Response
     */
    #[Route('/ajouter_plante', name: 'ajouter_plante', methods: ['GET', 'POST'])]
    public function ajouterPlante(
        Uploader $fileUploader,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $plante = new Plante();
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);

        //validation du formulaire et enregistrement d'une plante
        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('imagePlante')->getData();
            $user = $this->getUser();
            //upload de l'image d'une plante
            if ($fichier) {
                $imageName = $fileUploader->upload($fichier);
                $plante->setImagePlante($imageName);
            }
            $plante->setUser($user);
            //enregistrement de la plante
            $entityManager->persist($plante);
            $entityManager->flush();

            //Notification d'ajout de la plante
            $this->addFlash(
                'green',
                'Plante ajouter avec success!'
            );
            return $this->redirectToRoute('app_user_ajouter_plante', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/plante/ajouter.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    /**
     * Action permettant Modifier une plante
     * 
     * @param mixed|Notification $notifier,
     * @param mixed|Request $request
     * @param mixed|Plante $plante, 
     * @param mixed|EntityManagerInterface $entityManager,
     * @return Response
     */
    #[Route('/plante/{id}/edit', name: 'editer_plante', methods: ['GET', 'POST'])]
    public function definirBesoin(
        Notification $notifier,
        Request $request,
        Plante $plante,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);
        $user = $this->getUser();

        //validation du formulaire, enregistrement de la plante et notification
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plante);
            $entityManager->flush();

            //Notification de modification de la plante
            $this->addFlash(
                'green',
                'Plante modifier avec success!'
            );
            return $this->redirectToRoute('app_user_home', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->render('home/plante/modifier.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    /**
     * Action permettant de renvoyer l'interface de gestion en eau
     * 
     * @param mixed|Plante $plante, 
     * @return Response
     */
    #[Route('/plante/{id}/arrosage', name: 'arrosage_plante', methods: ['GET', 'POST'])]
    public function arrosagePlante(Plante $plante): Response
    {
        $user = $this->getUser();

        return $this->render('home/plante/arrosage.html.twig', [
            'user' => $user,
            'plante' => $plante,
            'besoins' => $plante->getBesoins(),
            'historiques' => $plante->getHistoriques(),
        ]);
    }
    /**
     * Methode de gestion de l'arrosage, des besoins en eau et l'historique d'arrosage d'une plante
     * 
     * @param mixed|int $p
     * @param mixed|int $q 
     * @param mixed|Plante $plante
     * @param mixed|Notification $notifier,
     * @param mixed|EntityManagerInterface $entityManager,
     * @return JsonResponse
     */
    #[Route('/plante/p/{id}/q/{q}/f/{f}', name: 'arrosage_besoin_plante', methods: ['GET', 'POST'])]
    public function marquagePlante(
        int $q,
        int $f,
        Plante $plante,
        Notification $notifier,
        EntityManagerInterface $entityManager
    ): JsonResponse {
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

        //Notification d'arrosage de la plante
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
        $this->addFlash(
            'green',
            'Notification d\'arrosage envoyer avec success!'
        );
        return new JsonResponse(
            ["message" => 'Besoins ajouté avec success!'],
            201,
            ["Content-type" => "application/json"]
        );
    }

    /**
     * Methode de notification
     * 
     * @param mixed|User $user
     * @param mixed|string $subject 
     * @param mixed|string $message
     * @param mixed|Notification $notifier,
     * @param mixed|string $template,
     * @param mixed|Besoin $besoin,
     * @return void
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
            compact('besoin', 'message')
        );
    }
}
