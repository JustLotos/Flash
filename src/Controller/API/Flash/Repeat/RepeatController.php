<?php

declare(strict_types=1);

namespace App\Controller\API\Flash;

use App\Controller\ControllerHelper;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Repeat\UseCase\DiscreteRepeat\Command;
use App\Domain\Flash\Repeat\UseCase\DiscreteRepeat\Handler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/repeat", name="repeat_") */
class RepeatController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/{cardId}/discrete/", name="discreteRepeat", methods={"POST"})
     * @ParamConverter("card", options={"mapping": {"cardId" : "id"}})
     * @param Request $request
     * @param Card $card
     * @param Handler $handler
     * @return Response
     */
    public function getAction(Request $request, Card $card, Handler $handler): Response
    {
//        $this->denyAccessUnlessGranted(CardVoter::VIEW, $card, CardVoter::NOT_FOUND_MESSAGE);
        /** @var Command $command */
        $command = $this->serializer->deserialize($request, Command::class);
        $handler->handle($card, $command);
        return $this->response($this->serializer->serialize($card));
    }
}
