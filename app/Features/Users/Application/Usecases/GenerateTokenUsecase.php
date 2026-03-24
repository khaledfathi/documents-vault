<?php
declare(strict_types=1);
namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\GenerateTokenContract;
use App\Features\Users\Application\Outputs\GenerateTokenOutput;
use App\Shared\Application\Contracts\PasswordHasherContract;
use App\Shared\Application\Contracts\TokenGeneratorContract;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final class GenerateTokenUsecase implements GenerateTokenContract
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasherContract $passwordHasher,
        private readonly TokenGeneratorContract $tokenGenerator
    ) {
    }
    public function execute(string $email, string $password, GenerateTokenOutput $presenter, string $tokenName = "token"): void
    {
        if (! $email || ! $password) {
            $presenter->onMissingInput("missing inputs : email or password is not provided");
            return;
        }
        try {
            $user = $this->userRepository->findByEmail($email);
            if ($user){
                if ($this->passwordHasher->check($password, $user->password)) {
                    $token = $this->tokenGenerator->generate($user->id ?? 0);
                    $presenter->onSuccess($token);
                    return; 
                }
            }
            $presenter->onCredentialFailed();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}