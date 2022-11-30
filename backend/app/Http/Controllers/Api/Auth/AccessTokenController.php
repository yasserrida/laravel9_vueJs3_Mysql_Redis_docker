<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Http\Controllers\AccessTokenController as ATC;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends ATC
{
    public function issueToken(ServerRequestInterface $request): JsonResponse
    {
        try {
            $username = $request->getParsedBody()['email'];
            $password = $request->getParsedBody()['password'];

            $user = User::where(function ($query) use ($username) {
                $query->orWhereRaw('LOWER(name) = ?', Str::lower($username))->orWhereRaw('LOWER(email) = ?', Str::lower($username));
            })->first();

            if (is_null($user)) {
                return response()->json(['CODE' => 400, 'MESSAGE' => 'Veuillez verfier votre email'], 400);
            }

            if ($user->active == 0) {
                return response()->json(['CODE' => 400, 'MESSAGE' => 'Ce compte est désactivé, Veuillez contacter votre responsable'], 400);
            }

            if (! Hash::check($password, $user->password)) {
                return response()->json(['CODE' => 400, 'MESSAGE' => 'Veuillez verfier votre mot de passe'], 400);
            }

            $tokenResponse = parent::issueToken($request);
            $data = json_decode($tokenResponse->getContent(), true);

            if (isset($data['error'])) {
                return response()->json(['CODE' => 400, 'MESSAGE' => 'Les informations utilisateur sont incorrects'], 400);
            }

            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            return response()->json(['CODE' => 400, 'MESSAGE' => 'Les informations utilisateur sont incorrects'], 400);
        } catch (OAuthServerException $e) {
            return response()->json(['CODE' => 400, 'MESSAGE' => 'Une erreur s\'est produite lors de l\'operation'], 400);
        } catch (Exception $e) {
            return response()->json(['CODE' => 400, 'MESSAGE' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
