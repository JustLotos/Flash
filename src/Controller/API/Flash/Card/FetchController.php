<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Card;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\UseCase\GetCards\Handler;
use App\Domain\Flash\Deck\Entity\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/flash/card") */
class FetchController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/{id}/", name="fetchCardsByDeck", methods={"GET"})
     * @param Handler $handler
     * @param Deck $deck
     * @return Response
     */
    public function fetchCardsByDeck(Handler $handler, Deck $deck): Response
    {
        $decks = $handler->handle($deck);
        return $this->response($this->serializer->serialize($decks, Card::GROUP_LIST));
    }
}