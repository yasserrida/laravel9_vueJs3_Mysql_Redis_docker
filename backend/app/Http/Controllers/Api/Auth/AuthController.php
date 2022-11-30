<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Passport\Client;

class AuthController extends BaseController
{
    private Client $client;

    public function __construct()
    {
        $this->client = Client::find(2);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['email' => 'required', 'password' => 'required'],
            ['*.required' => 'le champs :attribute est obligatoire']
        );

        if ($validator->fails()) {
            return ['CODE' => 400, 'MESSAGE' => 'Des informations sont manquantes', 'ERROR' => $this->getMessagesErrors($validator->errors())];
        }

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('email'),
            'password' => request('password'),
            'scope' => '*',
        ];

        $request->request->add($params);
        $proxy = Request::create('api/token', 'POST');
        $dispatch = Route::dispatch($proxy);

        if ($dispatch->getStatusCode() == '200') {
            $content = json_decode(strval($dispatch->getContent()));

            return [
                'CODE' => 200,
                'DATA' => [
                    'access_token' => $content->access_token,
                    'expireIn' => $content->expires_in,
                    'refresh_token' => $content->refresh_token,
                ],
            ];
        }

        return Route::dispatch($proxy);
    }

    public function logout(Request $request)
    {
        $token = Auth::guard('api')->user()->token();
        $token->revoke();
        $response = ['MESSAGE' => 'You have been successfully logged out!'];

        return response($response, 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6',
            'poste_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['CODE' => 400, 'MESSAGE' => 'Des informations sont manquantes', 'ERRORS' => $this->getMessagesErrors($validator->errors())], 400);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        User::create($request->toArray());

        return response()->json(['CODE' => 200, 'MESSAGE' => 'Your account has been successfully created']);
    }

    public function getMessagesErrors($errors)
    {
        $data = $errors->getMessages();
        $errors = [];
        foreach ($data as $key => $value) {
            $errors[] = $value[0];
        }

        return $errors;
    }

    public function refreshToken(Request $request)
    {
    }
}
