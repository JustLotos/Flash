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
use App\Service\RedisService;
use App\Service\ValidateService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Handler
{
    private $flusher;
    private $tokenizer;
    private $sender;
    private $validator;
    private $builder;
    private $generator;
    /** @var User */
    private $user;
    private $redisService;

    public function __construct(
        ValidateService $validator,
        FlushService $flusher,
        TokenService $tokenizer,
        MailSenderService $sender,
        MailBuilderService $builder,
        UrlGeneratorInterface $generator,
        RedisService $redisService
    ) {
        $this->flusher = $flusher;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->validator = $validator;
        $this->builder = $builder;
        $this->generator = $generator;
        $this->redisService = $redisService;
    }

    public function handle(Command $command, User $user): User
    {
        $token = $this->tokenizer->getToken();
        $email = new Email($command->email);
        $this->setToken($token, $email);
        $this->sendConfirmMessage($token);
        return $user;
    }

    public function setToken(string $token, Email $email) {
        $key = $this->user->getEmail()->getValue().'_change_email';
        $this->redisService->set($key, ['token' => $token, 'email' => $email->getValue()]);
    }

    public function sendConfirmMessage(string $token): void
    {
        $confirmLink = $this->generator->generate('changeEmailConfirm', ['token' => $token]);
        $subject = 'Запрос смены пароля в приложении Flash';

        $bodyMessage = $this->builder
            ->setParam('url', $confirmLink)
            ->setParam('token', $token)
            ->build('mail/user/change/email/request.html.twig');

        $message = BaseMessage::getDefaultMessage($this->user->getEmail(), $subject, $bodyMessage);
        $this->sender->send($message);
    }
}
