<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Password;

use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\User;
use App\Exception\BusinessException;
use App\Service\FlushService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Handler
{
    private $flusher;
    private $encoder;

    public function __construct(FlushService $flusher, UserPasswordEncoderInterface $encoder) {
        $this->flusher = $flusher;
        $this->encoder = $encoder;
    }

    public function handle(Command $command, User $user): void
    {
        if(!$this->encoder->isPasswordValid($user, $command->currentPassword)) {
            throw new BusinessException(['currentPassword' => 'password is incorrect']);
        }

        $user->setPassword(new Password($command->newPassword));
        $this->flusher->flush();
    }
}
