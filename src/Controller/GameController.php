<?php
namespace App\Controller;

use App\Form\NewGameType;
use App\Form\RollType;
use App\Repository\LaneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    public function __construct(
        private LaneRepository $laneRepository
    ) {
    }
    #[Route(path: "/game/{laneId}", name: "app_game")]
    public function game(Request $request, string $laneId): Response
    {
        $lane = $this->laneRepository->getById($laneId);

        $currentFrame = $lane->getCurrentFrame();
        $form = $this->createForm(RollType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $pins = $data['pins'];

            return $this->redirectToRoute('app_game_roll', [
                'pins' => $pins,
                'laneId' => $laneId,
            ]);
        }

        if (!$lane->isGameOver()) {
            return $this->render('game/index.html.twig', [
                'laneId' => $laneId,
                'playerIdx' => $currentFrame['playerIdx'],
                'frameIdx' => $currentFrame['frameIdx'],
                'scoreboardData' => $lane->getScoreboard(),
                'rollForm' => $form,
            ]);
        } else {
            $winners = $lane->getWinners();
            return $this->render('game/game_over.html.twig', [
                'laneId' => $laneId,
                'scoreboardData' => $lane->getScoreboard(),
                'winners' => $winners,
            ]);
        }
    }
    #[Route(path: '/game/{laneId}/roll/{pins}', name: 'app_game_roll')]
    public function submitRoll(int $pins, string $laneId): Response
    {
        $lane = $this->laneRepository->getById($laneId);
        try {
            $lane->roll($pins);
            $this->laneRepository->save($lane);
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute(
            'app_game',
            ['laneId' => $laneId]
        );

    }
    #[Route(path: "/game/new/{playerCount}", name: "app_game_new", priority: 1)]
    public function newLane(int $playerCount): Response
    {
        $lane = $this->laneRepository->createNew($playerCount);
        $id = $lane->getId();
        return $this->redirectToRoute(
            'app_game',
            ['laneId' => $id]
        );
    }
}