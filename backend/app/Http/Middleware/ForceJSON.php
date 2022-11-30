<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class ForceJSON
{
    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * JsonResponseMiddleware constructor.
     *
     * @param  ResponseFactory  $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');

        // Get the response
        $response = $next($request);

        //if (!$response instanceof JsonResponse) {

        // If the response is not strictly a JsonResponse, we make it
        $response_content = json_decode($response->content());
        //$data = $response_content['message'];

        $data = [];

        $data['code'] = isset($response_content->CODE) ? $response_content->CODE : $response->getstatusCode();

        if (isset($response_content->access_token)) {
            return $response;
        }

        if (isset($response_content->MESSAGE)) {
            $data['message'] = $response_content->MESSAGE;
        }

        if (isset($response_content->ERRORS)) {
            $data['erreurs'] = $response_content->ERRORS;
        }

        if (isset($response_content->DATA)) {
            $data['access_token'] = $response_content->DATA->access_token;
            $data['expireIn'] = $response_content->DATA->expireIn;
            $data['refresh_token'] = $response_content->DATA->refresh_token;
        }

        if (! isset($response_content->CODE) && isset($response_content->message)) {
            $data['message'] = $response_content->message;
        }

        return response()->json($data, $data['code'], [], JSON_UNESCAPED_UNICODE);
    }
}
