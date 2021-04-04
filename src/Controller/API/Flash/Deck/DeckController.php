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
class DeckController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/{id}/", name="getDeck", methods={"GET"})
     * @param Deck $deck
     * @return Response
     */
    public function getDeck(Deck $deck): Response
    {
        return $this->response($this->serializer->serialize($deck));
    }

    /**
     * @Route("/add/", name="addDeck", methods={"POST"})
     * @return Response
     */
    public function addDeck(): Response
    {
        return $this->response($this->getSimpleSuccessResponse());
    }

    /**
     * @Route("/update/", name="updateDeck", methods={"PUT"})
     * @return Response
     */
    public function updateDeck(): Response
    {
        return $this->response($this->getSimpleSuccessResponse());
    }

    /**
     * @Route("/delete/", name="deleteDeck", methods={"DELETE"})
     * @return Response
     */
    public function deleteDeck(): Response
    {
        return $this->response($this->getSimpleSuccessResponse());
    }
}