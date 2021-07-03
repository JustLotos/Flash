<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Repeat;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Record\Entity\Record;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Repeat\UseCase\DiscreteRepeat\Command as DiscreteRepeatCommand;
use App\Domain\Flash\Repeat\UseCase\DiscreteRepeat\Handler as DiscreteRepeatHandler;
use App\Domain\Flash\Repeat\UseCase\GetReadyQueue\Handler as GetReadyQueueHandler;
use App\Domain\Flash\Repeat\UseCase\DeleteRepeat\Handler as DeleteRepeatHandler;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/flash/repeat") */
class RepeatController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/{cardId}/discrete/", name="discreteRepeat", methods={"POST"})
     * @ParamConverter("card", options={"mapping": {"cardId" : "id"}})
     * @param Request $request
     * @param Card $card
     * @param DiscreteRepeatHandler $handler
     * @return Response
     * @throws Exception
     */
    public function getAction(Request $request, Card $card, DiscreteRepeatHandler $handler): Response
    {
//        $this->denyAccessUnlessGranted(CardVoter::VIEW, $card, CardVoter::NOT_FOUND_MESSAGE);
        /** @var DiscreteRepeatCommand $command */
        $command = $this->serializer->deserialize($request, DiscreteRepeatCommand::class);
        $handler->handle($card, $command);
        return $this->response($this->serializer->serialize($card, Card::GROUP_ONE));
    }

    /**
     * @Route("/queue/{id}/", name="readyQueueRepeat", methods={"GET"})
     * @param GetReadyQueueHandler $handler
     * @param Deck $deck
     * @return Response
     */
    public function getReadyForRepeatAction(GetReadyQueueHandler $handler, Deck $deck): Response
    {
        $collection = $handler->handle($deck);
        return $this->response($this->serializer->serialize($collection, [ Card::GROUP_LIST ]));
    }


    /**
     * @Route("/{id}/delete/", name="deleteRepeat", methods={"DELETE"})
     * @param Repeat $repeat
     * @param DeleteRepeatHandler $handler
     * @return Response
     */
    public function deleteRepeatAction(Repeat $repeat, DeleteRepeatHandler $handler): Response
    {
        $handler->handle($repeat);
        return $this->response($this->getSimpleSuccessResponse());
    }
}
