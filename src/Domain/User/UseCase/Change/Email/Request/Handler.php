<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Email\Request;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Service\TokenService;
use App\Service\FlushService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\MailService\MailSenderService;
use App\Service\ValidateService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Handler
{
    private $flusher;
    private $repository;
    private $tokenizer;
    private $sender;
    private $validator;
    private $builder;
    private $generator;

    public function __construct(
        ValidateService $validator,
        FlushService $flusher,
        UserRepository $repository,
        TokenService $tokenizer,
        MailSenderService $sender,
        MailBuilderService $builder,
        UrlGeneratorInterface $generator
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->validator = $validator;
        $this->builder = $builder;
        $this->generator = $generator;
    }

    public function handle(Command $command): User
    {
        $this->validator->validate($command);
        /** @var User $user */
        $user = $this->repository->getByEmail($command->oldEmail);
        /** @var ConfirmToken $token */
        $token = $this->tokenizer->generateTokenByClass(ConfirmToken::class);
        $user->requestChangeEmail($token, new Email($command->newEmail));
        $this->flusher->flush();
        $this->sendConfirmMessage($user);
        return $user;
    }

    public function sendConfirmMessage(User $user): void
    {
        $token = $user->getConfirmToken()->getToken();
        $confirmLink = $this->generator->generate('changeEmailConfirm', ['token' => $token]);
        $subject = 'Запрос смены пароля в приложении Flash';

        $bodyMessage = $this->builder
            ->setParam('url', $confirmLink)
            ->setParam('token', $user->getConfirmToken()->getToken())
            ->build('mail/user/change/email/request.html.twig');

        $message = BaseMessage::getDefaultMessage($user->getEmail(), $subject, $bodyMessage);
        $this->sender->send($message);
    }
}
