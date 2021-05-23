<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Card;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\UseCase\FetchCardsByDeck\Handler;
use App\Domain\Flash\Deck\Entity\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/** @Route(value="api/flash/cards") */
class FetchController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/{deckId}/", name="fetchCardsByDeck", methods={"GET"})
     * @ParamConverter("deck", options={"mapping": {"deckId" : "id"}})
     * @param Handler $handler
     * @param Deck $deck
     * @return Response
     */
    public function fetchCardsByDeck(Handler $handler, Deck $deck): Response
    {
        return $this->response($this->serializer->serialize($handler->handle($deck), Card::GROUP_LIST));
    }
}