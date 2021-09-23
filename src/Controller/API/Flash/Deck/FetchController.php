<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Deck;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Deck\DeckRepository;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Deck\UseCase\GetDecks\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/flash/") */
class FetchController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/deck/", name="fetchDecks", methods={"GET"})
     * @param Handler $handler
     * @return Response
     */
    public function fetchDecks(Handler $handler): Response{
        $decks = $handler->handle();
        return $this->response($this->serializer->serialize($decks, Deck::GROUP_LIST));
    }

    /**
     * @Route("/community/deck/", name="fetchCommunityDecks", methods={"GET"})
     * @param DeckRepository $repository
     * @return Response
     */
    public function fetchCommunityDecks(DeckRepository $repository): Response {
        $decks = $repository->findBy(['publish' => true]);
        return $this->response($this->serializer->serialize($decks));
    }
}