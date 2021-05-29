<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Confirm;

use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Exception\ValidationException;
use App\Service\FlushService;
use App\Service\MailService\MailSenderService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\RedisService;
use Symfony\Component\HttpFoundation\Response;

class Handler
{
    private $repository;
    private $flusher;
    private $sender;
    private $builder;
    private $redis;
    /** @var User */
    private $user;

    public function __construct(
        UserRepository $repository,
        FlushService $flusher,
        MailSenderService $sender,
        MailBuilderService $builder,
        RedisService $redis
    ) {
        $this->repository = $repository;
        $this->flusher = $flusher;
        $this->sender = $sender;
        $this->builder = $builder;
        $this->redis = $redis;
    }

    public function handle(Command $command): string
    {
        $this->user = $this->repository->findOneBy(['email' => $command->email]);
        if($this->user->getStatus()->isWait()) {
            return $this->confirm($command);
        } else {
            return 'alreadyConfirm';
        }
    }

    public function confirm(Command $command): string
    {
        $this->checkExistToken($command->token);
        $this->user->confirmRegisterByEmail();
        $this->flusher->flush();
        $this->sendMessage();
        return 'confirm';
    }

    public function checkExistToken(string $token): void
    {
        $redisToken = $this->redis->get($this->user->getEmail()->getValue().'_register');
        if(!$redisToken) {
            throw new ValidationException(json_encode(['confirm' => 'is not requested']), Response::HTTP_NOT_FOUND);
        }

        if($redisToken !== $token) {
            throw new ValidationException(json_encode(['token' => 'token is expired']), Response::HTTP_NOT_FOUND);
        }
        $this->redis->del($this->user->getEmail()->getValue().'_register');
    }

    public function sendMessage(): void
    {
        $body = $this->builder->build('mail/user/reset/byEmail/confirm.html.twig');
        $message = BaseMessage::getDefaultMessage($this->user->getEmail(),'Подтверждение регистрации', $body);
        $this->sender->send($message);
    }
}
