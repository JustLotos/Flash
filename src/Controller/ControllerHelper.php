<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\LearnerRepository;
use App\Domain\User\Entity\User;
use App\Service\ValidateService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\SerializeService;

trait ControllerHelper
{
    private $serializer;
    private $validator;
    /** @var LearnerRepository */
    private $learnerRepository;

    public function __construct(
        SerializeService $serializer,
        ValidateService $validator,
        LearnerRepository $learnerRepository
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->learnerRepository = $learnerRepository;
    }

    public function extractData(Request $request, string $commandName) {
        $command = $this->serializer->deserialize($request, $commandName);
        $this->validator->validate($command);
        return $command;
    }

    public function getLearner(): Learner
    {
        /** @var User $user */
        $user = $this->getUser();
        $id = new Id($user->getId()->getValue());
        $learner = $this->learnerRepository->find($id->getValue());;
        return $learner;
    }

    public function response(string $content, int $statusCode = Response::HTTP_OK): Response
    {
        return new Response($content, $statusCode);
    }

    public function getSimpleSuccessResponse()
    {
        return json_encode(['success' => true]);
    }

    public function getSimpleErrorResponse(array $errors)
    {
        return json_encode(['success' => false, 'errors'=>json_encode($errors)]);
    }
}
