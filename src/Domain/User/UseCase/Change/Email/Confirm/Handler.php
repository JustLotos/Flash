<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Email\Confirm;

use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Service\PasswordEncoder;
use App\Exception\BusinessException;
use App\Service\FlushService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\MailService\MailSenderService;
use App\Service\RedisService;
use App\Service\ValidateService;
use DateTimeImmutable;
use DomainException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Handler
{
    private $repository;
    private $flusher;
    private $validator;
    private $sender;
    private $builder;
    private $generator;
    private $redis;
    /** @var User */
    private $user;

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

    public function handle(Command $command, User $user): void
    {
        $this->user = $user;
        $email = $this->getEmailFromRedis($command->token);
        $this->user->confirmChangeEmail(new DateTimeImmutable(), $email);
        $this->flusher->flush();
        $this->sendConfirmMessage();
    }

    public function getEmailFromRedis(string $token): Email
    {
        $key = $this->user->getEmail()->getValue().'_change_email';

        if(!($redisData = $this->redis->get($key))) {
            throw new BusinessException(['token' => 'change email is not requested or expired']);
        }

        $redisData = unserialize($redisData);
        if($redisData['token'] !== $token) {
            throw new BusinessException(['token' => 'token is not valid']);
        }

        return new Email($redisData['email']);
    }

    public function sendConfirmMessage(): void
    {
        $subject = 'Успешная смена email в приложении Flash';
        $body = $this->builder->build('mail/user/change/email/confirm.html.twig');
        $message = BaseMessage::getDefaultMessage($this->user->getEmail(), $subject, $body);
        $this->sender->send($message);
    }
}
