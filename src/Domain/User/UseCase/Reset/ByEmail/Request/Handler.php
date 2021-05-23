<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Reset\ByEmail\Request;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Service\TokenService;
use App\Service\FlushService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\MailService\MailSenderService;
use App\Service\RedisService;
use App\Service\ValidateService;
use DomainException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Handler
{
    private $flusher;
    private $repository;
    private $tokenizer;
    private $sender;
    private $builder;
    private $generator;
    private $redis;
    /** @var User $user */
    private $user;

    public function __construct(
        FlushService $flusher,
        UserRepository $repository,
        TokenService $tokenizer,
        MailSenderService $sender,
        MailBuilderService $builder,
        UrlGeneratorInterface $generator,
        RedisService $redis
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->builder = $builder;
        $this->generator = $generator;
        $this->redis = $redis;
    }

    public function handle(Command $command): User
    {
        $this->user = $this->repository->getByEmail($command->email);
        $token = $this->tokenizer->getToken();

        $this->setToken($token);

        $this->user->requestResetPassword();
        $this->flusher->flush();
        $this->sendConfirmMessage($token);
        return $this->user;
    }

    public function setToken(string $token): void
    {
        $key = $this->user->getEmail()->getValue().'_reset_password';
        if($this->redis->get($key)) {
            throw new DomainException(json_encode(['email' => 'already requested, check email box']));
        }
        $this->redis->set($key, $token, (int)getenv('REDIS_DEFAULT_TTL'));
    }

    public function sendConfirmMessage(string $token): void
    {
        $url = $this->generator->generate(
            'resetByEmailGetForm',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $message = BaseMessage::getDefaultMessage(
            $this->user->getEmail(),
            'Восстановление доступа в приложении FLashBack',
            $this->builder
                ->setParam('url', $url)
                ->setParam('token', $token)
                ->build('mail/user/reset/byEmail/request.html.twig')
        );

        $this->sender->send($message);
    }
}
