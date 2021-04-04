<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Deck;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Deck\UseCase\GetDecks\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/flash/deck") */
class FetchController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/", name="fetchDecks", methods={"GET"})
     * @param Handler $handler
     * @return Response
     */
    public function fetchDecks(Handler $handler): Response{
        $decks = $handler->handle();
        return $this->response($this->serializer->serialize($decks, Deck::GROUP_LIST));
    }
}