<?php

declare(strict_types=1);

namespace App\Controller\API\Flash\Deck;

use App\Controller\ControllerHelper;
use App\Domain\ElasticSearch\BaseIndex\Service\ElasticService;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\DeckRepository;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Deck\UseCase\GetDecks\Handler;
use App\Domain\Flash\Record\Entity\Record;
use App\Domain\Flash\Repeat\Entity\Repeat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/flash/fetch") */
class FetchController extends AbstractController
{
    use ControllerHelper;

    /**
     * @Route("/standart/deck/", name="fetchDecks", methods={"GET"})
     * @param Handler $handler
     * @return Response
     */
    public function fetchDecks(Handler $handler): Response
    {
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

    /**
     * @Route("/es/deck/", name="fetchFormEsDecks", methods={"GET"})
     * @param ElasticService $elasticService
     * @return Response
     */
    public function fetchFormEsDecks(ElasticService $elasticService): Response {
        $start = microtime(true);

        $result = $elasticService->getInstance()->search(['index' => 'deck']);
        $response = [];

        foreach ($result['hits']['hits'] as $esDeck) {
            $response['decks'][] = $esDeck['_source'];
        }

        $response['timeTracking']['executed'] = microtime(true) - $start;;
        return $this->json($response);
    }


    /**
     * @Route("/db/deck/", name="fetchFormDbDecks", methods={"GET"})
     * @param DeckRepository $deckRepository
     * @return Response
     */
    public function fetchFormDbDecks(DeckRepository $deckRepository): Response {
        $start = microtime(true);
        $response = [];
        $decks = $deckRepository->findAll();
        $response['decks'] = json_decode($this->serializer->serialize($decks,  [
            Deck::GROUP_LIST,
            Deck::GROUP_ONE,
            Card::GROUP_ONE,
            Repeat::GROUP_ONE,
            Record::GROUP_ONE
        ]));
        $response['timeTracking']['executed'] = microtime(true) - $start;
        return $this->json($response);
    }

}