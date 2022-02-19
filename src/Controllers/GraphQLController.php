<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Controllers;

use GraphQL\GraphQL;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use TheCodingMachine\GraphQLite\Context\Context;
use TheCodingMachine\GraphQLite\SchemaFactory;
use Thibaultjunin\Api\Api;
use Thibaultjunin\Api\Attributes\Auth;
use Thibaultjunin\Api\Auth\GraphQL\AuthenticationService;
use Thibaultjunin\Api\Auth\GraphQL\AuthorizationService;

class GraphQLController extends Controller
{
    #[Auth([Auth::EVERYONE])] // We allow everyone as some route may be public
    public function query(Request $request, Response $response, array $args)
    {
        $filesystem = new FilesystemAdapter('graphql', 0, Api::getInstance()->getCacheFolder());
        $cache = new Psr16Cache($filesystem);

        $factory = new SchemaFactory($cache, $this->getContainer());
        $factory->addControllerNamespace('App\\Controllers\\GraphQL')
            ->addTypeNamespace('App\\')
            ->setAuthenticationService(new AuthenticationService($request->getAttribute("user")))
            ->setAuthorizationService(new AuthorizationService($request->getAttribute("user")));

        if (Api::getInstance()->isDevMode()) {
            $factory->devMode();
        } else {
            $factory->prodMode();
        }

        $schema = $factory->createSchema();

        $input = $request->getParsedBody();
        $variableValues = $input['variables'] ?? null;

        $result = GraphQL::executeQuery($schema, $input['query'], null, new Context(), $variableValues);

        return $response->withJson($result->toArray());
    }
}