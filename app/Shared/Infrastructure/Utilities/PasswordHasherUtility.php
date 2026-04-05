<?php
declare (strict_types= 1);
namespace App\Shared\Infrastructure\Utilities;

use App\Shared\Application\Contracts\Utilities\PasswordHasherContract;
use Illuminate\Support\Facades\Hash;

final class PasswordHasherUtility implements PasswordHasherContract {
    public function check (string $password , string $hashedPassword):bool{
        return Hash::check($password,$hashedPassword);
    }
    public function hash($password):string{
        return Hash::make($password);
    }
}
