<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Resend;

use App\Domain\Flusher;
use App\Domain\User\Entity\User;
use App\Domain\User\Service\TokenService;
use App\Domain\User\UserRepository;
use App\Exception\ValidationException;
use App\Service\FlushService;
use App\Service\MailService\MailSenderService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\RedisService;
use DomainException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Handler
{
    private $flusher;
    private $repository;
    private $sender;
    private $tokenizer;
    private $dispatcher;
    private $builder;
    private $generator;
    /** @var RedisService */
    private $redis;
    /** @var User */
    private $user;

    public function __construct(
        TokenService $tokenizer,
        UserRepository $repository,
        FlushService $flusher,
        MailSenderService $sender,
        MailBuilderService $builder,
        EventDispatcherInterface $dispatcher,
        UrlGeneratorInterface $generator,
        RedisService $redis
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
        $this->sender = $sender;
        $this->tokenizer = $tokenizer;
        $this->dispatcher = $dispatcher;
        $this->builder = $builder;
        $this->generator = $generator;
        $this->redis = $redis;
    }

    public function handle(Command $command): User
    {
        $this->user = $this->repository->getByEmail($command->email);

        if(!$this->user->getStatus()->isWait()) {
            throw new DomainException(
                json_encode(['user' => 'user is already active']),
                Response::HTTP_UNPROCESSABLE_ENTITY)
            ;
        }

        $token = $this->tokenizer->getToken();
        $this->setToken($token);

        $this->sendConfirmMessage($token);
        return $this->user;
    }

    private function setToken($token): void
    {
        $key = $this->user->getEmail()->getValue().'_register';
        if(!$this->redis->get($key)) {
            throw new DomainException(
                json_encode(['token' => 'token is not requested']),
                Response::HTTP_NOT_FOUND
            );
        }

        $this->redis->set($key, $token, (int)getenv('REDIS_DEFAULT_TTL'));
    }

    public function sendConfirmMessage(string $token): void
    {
        $url = getenv('DEFAULT_HOST').$this->generator->generate('registerByEmailConfirm', [
            'token' => $token,
            'email' => $this->user->getEmail()->getValue()
        ]);

        $message = BaseMessage::getDefaultMessage(
            $this->user->getEmail(),
            'Повторная отправка для подтверждения регистрации',
            $this->builder
                ->setParam('url', $url)
                ->setParam('token', $token)
                ->build('mail/user/register/byEmail/request.html.twig')
        );

        $this->sender->send($message);
    }
}
