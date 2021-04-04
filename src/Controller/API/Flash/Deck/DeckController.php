<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Deck;

use App\Controller\API\Flash\FlashControllerHelper;
use App\Controller\ControllerHelper;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Deck\UseCase\AddDeck\Handler as AddDeckHandler;
use App\Domain\Flash\Deck\UseCase\AddDeck\Command as AddDeckCommand;
use App\Domain\Flash\Deck\UseCase\UpdateDeck\Command as UpdateDeckCommand;
use App\Domain\Flash\Deck\UseCase\UpdateDeck\Handler;
use App\Service\SerializeService;
use App\Service\ValidateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param AddDeckHandler $handler
     * @param Request $request
     * @return Response
     */
    public function addDeck(AddDeckHandler $handler, Request $request): Response
    {
        /** @var AddDeckCommand $command */
        $command = $this->serializer->deserialize($request, AddDeckCommand::class);
        $this->validator->validate($command);
        /** @var User $user */
        $user = $this->getUser();
        $deck = $handler->handle($command, new Id($user->getId()->getValue()));
        return $this->response(
            $this->serializer->serialize($deck, Deck::GROUP_ONE),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/{id}/update/", name="updateDeck", methods={"PUT"})
     * @param Request $request
     * @param Handler $handler
     * @param Deck $deck
     * @return Response
     */
    public function updateDeck(Request $request, Handler $handler, Deck $deck): Response
    {
        /** @var UpdateDeckCommand $command */
        $command = $this->extractData($request,UpdateDeckCommand::class);
        $deck = $handler->handle($command, $deck);
        return $this->response($this->serializer->serialize($deck, Deck::GROUP_ONE));
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