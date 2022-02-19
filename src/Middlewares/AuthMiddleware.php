<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Thibaultjunin\Api\Api;
use Thibaultjunin\Api\Attributes\Auth;
use Thibaultjunin\Api\Utils\StringUtils;

class AuthMiddleware
{

    private string $realm;
    private string $attribute_name;

    /**
     * @param string $realm
     * @param string $attribute_name
     */
    public function __construct(string $realm = "API", string $attribute_name = "user")
    {
        $this->realm = $realm;
        $this->attribute_name = $attribute_name;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $requestHandler)
    {
        // Getting the route callable
        $callable = $request->getAttribute('__route__')->getCallable();

        // New reflection method to get Auth Attributes
        $method = new \ReflectionMethod(StringUtils::before(":", $callable), StringUtils::after(":", $callable));
        $authAttributes = $method->getAttributes(Auth::class);

        // If no auth attribute is present, consider everyone is allowed.
        if (empty($authAttributes)) {
            return $requestHandler->handle($request);
        }

        // Creating a new instance of Auth
        $auth = $authAttributes[0]->newInstance();

        if (in_array(Auth::EVERYONE, $auth->getRoles()) && empty($request->getHeaderLine("Authorization"))) {
            // Everyone is allowed.
            return $requestHandler->handle($request);
        }

        $user = null;
        $method = "Unknown";
        $roles = null;

        if (preg_match("/Bearer\s+(.*)$/i", $request->getHeaderLine("Authorization"), $matches)) {
            $bearer = $matches[1];
            $method = "Bearer";

            if (Api::getInstance()->getAuth()->authenticateToken($bearer)) {
                $user = Api::getInstance()->getAuth()->getUserIdForToken($bearer);
                $roles = Api::getInstance()->getAuth()->getRolesForToken($bearer);
            }
        }

        if ($user == null || $roles == null) {
            $responseFactory = new ResponseFactory();
            return $responseFactory->createResponse(401)
                ->withHeader("WWW-Authenticate", sprintf("%s realm=%s", $method, $this->realm))
                ->withHeader("Content-Type", "application/json")
                ->getBody()->write(json_encode([
                    "success" => false,
                    "errors" => ["Authentication failed"]
                ]));
        }

        $request = $request->withAttribute($this->attribute_name, $user);

        if (in_array(Auth::ALL, $roles)) {
            return $requestHandler->handle($request);
        }

        foreach ($roles as $userRole) {
            if (in_array($userRole, $auth->getRoles())) {
                return $requestHandler->handle($request);
            }
        }

        $responseFactory = new ResponseFactory();
        return $responseFactory->createResponse(403)
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode([
                "success" => false,
                "errors" => ["Forbidden"]
            ]));
    }

}