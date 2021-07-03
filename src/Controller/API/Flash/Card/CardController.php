<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Card;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\UseCase\AddCardWithRecords\Command as AddCardWithRecordsCommand;
use App\Domain\Flash\Card\UseCase\AddCardWithRecords\Handler as AddCardWithRecordsHandler;
use App\Domain\Flash\Card\UseCase\UpdateCardWithRecords\Command as UpdateCardWithRecordsCommand;
use App\Domain\Flash\Card\UseCase\UpdateCardWithRecords\Handler as UpdateCardWithRecordsHandler;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Card\UseCase\AddCard\Handler as AddCardHandler;
use App\Domain\Flash\Card\UseCase\DeleteCard\Handler as DeleteCardHandler;
use App\Domain\Flash\Record\Entity\Record;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Service\AnswerMangerService\AnswerManagerService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/flash/card") */
class CardController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/{id}/", name="getCard", methods={"GET"})
     * @param Card $card
     * @return Response
     */
    public function getCard(Card $card): Response
    {
        return $this->response($this->serializer->serialize($card, [
            Card::GROUP_ONE,
            Card::GROUP_FOR_REPEAT,
            Record::GROUP_ONE,
            Repeat::GROUP_ONE
        ]));
    }

    /**
     * @Route("/{id}/repeatInfo/", name="getCardRepeatInfo", methods={"GET"})
     * @param Card $card
     * @return Response
     * @throws Exception
     */
    public function getCardRepeatInfo(Card $card): Response
    {
        $averageScore = 0;
        /** @var Repeat $repeat */
        foreach ($card->getRepeats() as $repeat) {
            $averageScore += $repeat->getRatingScore();
        }

        $repeatInfo = [
            'repeatCount' => $card->getRepeats()->count(),
            'averageScore' => $averageScore / $card->getRepeats()->count()
        ];

        return $this->response($this->serializer->serialize($repeatInfo));
    }

    /**
     * @Route("/{deckId}/add/", name="addCard", methods={"POST"})
     * @ParamConverter("deck", options={"mapping": {"deckId" : "id"}})
     * @param AddCardHandler $handler
     * @param Deck $deck
     * @return Response
     */
    public function addCard(AddCardHandler $handler, Deck $deck): Response
    {
        $card = $handler->handle($deck);
        return $this->response(
            $this->serializer->serialize($card, Card::GROUP_ONE),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/{deckId}/add/records/", name="addCardWithRecords", methods={"POST"})
     * @ParamConverter("deck", options={"mapping": {"deckId" : "id"}})
     * @param AddCardWithRecordsHandler $handler
     * @param Deck $deck
     * @param Request $request
     * @return Response
     */
    public function addCardWithRecords(AddCardWithRecordsHandler $handler, Deck $deck, Request $request): Response
    {
        /** @var AddCardWithRecordsCommand $command */
        $command = $this->extractData($request, AddCardWithRecordsCommand::class);
        $card = $handler->handle($deck, $command);

        return $this->response(
            $this->serializer->serialize($card, [Card::GROUP_ONE, Record::GROUP_ONE]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/{id}/update/records/", name="updateCardWithRecords", methods={"PUT"})
     * @param UpdateCardWithRecordsHandler $handler
     * @param Card $card
     * @param Request $request
     * @return Response
     */
    public function updateCardWithRecords(UpdateCardWithRecordsHandler $handler, Card $card, Request $request): Response
    {
        /** @var UpdateCardWithRecordsCommand $command */
        $command = $this->extractData($request, UpdateCardWithRecordsCommand::class);
        $card = $handler->handle($card, $command);
        return $this->response(
            $this->serializer->serialize($card, [Card::GROUP_ONE, Record::GROUP_ONE]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/{id}/delete/", name="deleteCard", methods={"DELETE"})
     * @param Card $card
     * @param DeleteCardHandler $handler
     * @return Response
     */
    public function deleteCard(Card $card, DeleteCardHandler $handler): Response
    {
        $handler->handle($card);
        return $this->response($this->getSimpleSuccessResponse());
    }
}