<?php
namespace App\Controller;

use App\Form\LoadGameType;
use App\Form\NewGameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LobbyController extends AbstractController
{
    #[Route(path: "/", name: "app_home")]
    #[Route(path: "/lobby", name: "app_lobby")]
    public function enterLobby(Request $request): Response
    {
        $newGameForm = $this->createForm(NewGameType::class, options: [
            'action' => $this->generateUrl('app_lobby_new')
        ]);
        $loadGameForm = $this->createForm(LoadGameType::class, options: [
            'action' => $this->generateUrl('app_game_load')
        ]);


        return $this->render(
            "lobby/index.html.twig",
            [
                'newGameForm' => $newGameForm,
                'loadGameForm' => $loadGameForm
            ]
        );
    }
    #[Route('/lobby/new', name: 'app_lobby_new', methods: ['POST'])]
    public function startNew(Request $request): Response
    {
        $form = $this->createForm(NewGameType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $playerCount = $data["player_count"] ?? 0;

            return $this->redirectToRoute(
                "app_game_new",
                ['playerCount' => $playerCount]
            );
        }

        $this->addFlash('error', 'Please correct the errors in the new game form.');
        return $this->redirectToRoute('app_game_setup');
    }

    #[Route('/lobby/load', name: 'app_game_load', methods: ['POST'])]
    public function loadGame(Request $request): Response
    {
        $loadGameForm = $this->createForm(LoadGameType::class);
        $loadGameForm->handleRequest($request);

        if ($loadGameForm->isSubmitted() && $loadGameForm->isValid()) {
            $data = $loadGameForm->getData();
            $laneId = $data['lane_id'];
            return $this->redirectToRoute(
                'app_game',
                ['laneId' => $laneId]
            );
        }
        $this->addFlash('error', 'Please correct the errors in the load game form.');
        return $this->redirectToRoute('app_game_setup');
    }
}