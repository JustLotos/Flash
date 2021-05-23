<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Request;

use App\Domain\Flusher;
use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\Types\Status;
use App\Domain\User\Entity\User;
use App\Domain\User\Events\UserCreatedEvent;
use App\Domain\User\Service\TokenService;
use App\Domain\User\UserRepository;
use App\Exception\ValidationException;
use App\Service\FlushService;
use App\Service\MailService\MailSenderService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\RedisService;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
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
        $this->user = User::createByEmail(
            Id::next(),
            new DateTimeImmutable(),
            Role::createUser(),
            new Email($command->email),
            new Password($command->password),
            Status::createWait()
        );

        $this->user->requestRegisterByEmail();

        $token = $this->tokenizer->getToken();
        $this->setToken($token);

        $event = new UserCreatedEvent($this->user);
        $this->dispatcher->dispatch($event, UserCreatedEvent::NAME);

        $this->repository->add($this->user);
        $this->flusher->flush();

        $this->sendConfirmMessage($token);
        return $this->user;
    }

    private function setToken($token): void
    {
        $key = $this->user->getEmail()->getValue().'_register';
        if($this->redis->get($key)) {
            throw new ValidationException(json_encode([
                'errors' => [ 'token' => 'token already exist' ]
            ]), Response::HTTP_NOT_FOUND);
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
            'Подтверждение регистрации',
            $this->builder
                ->setParam('url', $url)
                ->setParam('token', $token)
                ->build('mail/user/register/byEmail/request.html.twig')
        );

        $this->sender->send($message);
    }
}
