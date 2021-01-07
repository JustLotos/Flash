<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Email\Request;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Service\TokenService;
use App\Exception\BusinessException;
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
        $this->user = $user;

        if($this->user->getEmail()->getValue() === $command->email) {
            throw new ValidationException(
                json_encode(['email' => 'email the same']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $token = $this->tokenizer->getToken();
        $this->setToken($token,  new Email($command->email));
        $this->sendConfirmMessage($token);
        return $user;
    }

    public function setToken(string $token, Email $email) {
        $key = $this->user->getEmail()->getValue().'_change_email';
        if($this->redisService->get($key)) {
            throw new BusinessException(['token' => 'token already send']);
        }

        $this->redisService->set($key, serialize(['token' => $token, 'email' => $email->getValue()]));
    }

    public function sendConfirmMessage(string $token): void
    {
//        $confirmLink = $this->generator->generate(
//            'changeEmailConfirm',
//            ['token' => $token],
//            UrlGeneratorInterface::ABSOLUTE_URL
//        );

        $confirmLink = getenv('DEFAULT_HOST').'/lk/?token='.$token.'&changeEmail=Y';
        $subject = 'Запрос смены электронного адреса в приложении Flash';

        $bodyMessage = $this->builder
            ->setParam('url', $confirmLink)
            ->setParam('token', $token)
            ->build('mail/user/change/email/request.html.twig');

        $message = BaseMessage::getDefaultMessage($this->user->getEmail(), $subject, $bodyMessage);
        $this->sender->send($message);
    }
}
