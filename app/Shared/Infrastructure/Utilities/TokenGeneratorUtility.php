<?php
declare (strict_types= 1);
namespace App\Shared\Infrastructure\Utilities;

use App\Shared\Application\Contracts\Utilities\TokenGeneratorContract;
use App\Shared\Infrastructure\Models\User;

use function Illuminate\Support\minutes;

final class TokenGeneratorUtility implements TokenGeneratorContract {

    public function generate(int $userId): string | null
    {
        if(! $user = User::find($userId)) return  null;
        return $user->createToken($request->token_name ?? 'token_name')->plainTextToken;
    }

    public function destroyCurrentToken () :void {
        auth()->user()->currentAccessToken()->delete();
    }
}
