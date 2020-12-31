<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Reset\ByEmail\Confirm;

use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Service\PasswordEncoder;
use App\Exception\ValidationException;
use App\Service\FlushService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\MailService\MailSenderService;
use App\Service\RedisService;
use App\Service\ValidateService;
use DomainException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Handler
{
    private $repository;
    private $flusher;
    private $validator;
    private $sender;
    private $builder;
    private $generator;
    /** @var User */
    private $user;
    private $redis;

    public function __construct(
        UserRepository $repository,
        ValidateService $validator,
        FlushService $flusher,
        MailSenderService $sender,
        MailBuilderService $builder,
        UrlGeneratorInterface $generator,
        RedisService $redis
    ) {
        $this->repository = $repository;
        $this->flusher = $flusher;
        $this->validator = $validator;
        $this->sender = $sender;
        $this->builder = $builder;
        $this->generator = $generator;
        $this->redis = $redis;
    }

    public function handle(Command $command): void
    {
        $this->user = $this->repository->findOneBy(['email' => $command->email]);
        $this->checkExistToken($command->token);

        $this->user->confirmResetPassword(new Password($command->password));
        $this->flusher->flush();
        $this->sendSuccessMessage();
    }

    public function checkExistToken(string $token): void
    {
        $redisToken = $this->redis->get($this->user->getEmail()->getValue().'_reset_password');
        if(!$redisToken) {
            $this->redis->del($this->user->getEmail()->getValue().'_reset_password');
            throw new DomainException(json_encode(['reset'=> 'is not requested']), Response::HTTP_NOT_FOUND);
        }

        if($redisToken !== $token) {
            $this->redis->del($this->user->getEmail()->getValue().'_reset_password');
            throw new ValidationException(json_encode(['token' => 'token is expired']), Response::HTTP_NOT_FOUND);
        }

    }

    public function sendSuccessMessage(): void
    {
        $message = BaseMessage::getDefaultMessage(
            $this->user->getEmail(),
            'Успешная смена проля в приложении Flash',
            $this->builder->build('mail/user/reset/byEmail/confirm.html.twig')
        );

        $this->sender->send($message);
    }
}
