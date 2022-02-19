<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Thibaultjunin\Api\Attributes\APIField;
use Thibaultjunin\Api\Helpers\Helper;
use Validator\Validator;

abstract class ApiController
{

    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function listElements(Helper $helper, Request $request, Response $response, array $args): Response|ResponseInterface
    {
        $query = null; // FIXME:

        $page = 1;
        $per_page = 10;

        if (isset($request->getQueryParams()['page'])) {
            $page = intval($request->getQueryParams()['page']);
        }
        if (isset($request->getQueryParams()['per_page'])) {
            $per_page = intval($request->getQueryParams()['per_page']);
        }

        return $response->withJson([
            "success" => true,
            "data" => [
                "elements" => $helper->list($query, $page, $per_page),
            ],
            "total" => $helper->count(),
        ]);
    }

    public function addElement(Helper $helper, Request $request, Response $response, array $args): Response|ResponseInterface
    {
        $validator = new Validator($request->getParsedBody());
        $fields = $this->getHelperFields($helper);
        $this->addValidation($fields, $validator);

        if (!$validator->isValid()) {
            return $response->withJson([
                "success" => false,
                "errors" => $validator->getErrors(),
            ])->withStatus(400);
        }

        foreach ($fields as $field => $fl) {
            $helper->offsetSet($field, $request->getParsedBody()[$field]); // FIXME: SECURITY: Sanitize input and block not allowed variables
        }

        if (!$helper->save()) {
            return $response->withJson([
                "success" => false,
            ])->withStatus(500);
        }

        return $response->withJson([
            "success" => true,
            "data" => $helper->jsonSerialize(),
        ]);
    }

    private function getHelperFields(Helper $helper)
    {
        $fields = [];
        $class = new \ReflectionClass($helper);
        foreach ($class->getProperties() as $property) {
            $attributes = $property->getAttributes(APIField::class);
            if (!empty($attributes)) {
                $field = $attributes[0]->newInstance();
                $fields[$property->getName()] = $field->getRequirements();
            }
        }
        return $fields;
    }

    private function addValidation(array $fields, Validator &$validator)
    {
        foreach ($fields as $field => $flags) {
            if ($flags == NULL) {
                continue;
            }
            if (APIField::isFlagSet($flags, APIField::REQUIRED)) {
                $validator->required($field);
            }
            if (APIField::isFlagSet($flags, APIField::NOT_EMPTY)) {
                $validator->notEmpty($field);
            }
            if (APIField::isFlagSet($flags, APIField::NOT_NULL)) {
                $validator->notNull($field);
            }
            if (APIField::isFlagSet($flags, APIField::SLUG)) {
                $validator->slug($field);
            }
            if (APIField::isFlagSet($flags, APIField::URL)) {
                $validator->url($field);
            }
            if (APIField::isFlagSet($flags, APIField::EMAIL)) {
                $validator->email($field);
            }
            if (APIField::isFlagSet($flags, APIField::ARRAY)) {
                $validator->array($field);
            }
            if (APIField::isFlagSet($flags, APIField::INTEGER)) {
                $validator->integer($field);
            }
            if (APIField::isFlagSet($flags, APIField::FLOAT)) {
                $validator->float($field);
            }
            if (APIField::isFlagSet($flags, APIField::BOOLEAN)) {
                $validator->boolean($field);
            }
            if (APIField::isFlagSet($flags, APIField::ALPHANUMERICAL)) {
                $validator->alphaNumerical($field);
            }
        }
    }

    public function updateElement(Helper $helper, Request $request, Response $response, array $args): Response|ResponseInterface
    {
        $validator = new Validator($request->getParsedBody());
        $fields = $this->getHelperFields($helper);
        $this->addValidation($fields, $validator);

        if (!$validator->isValid()) {
            return $response->withJson([
                "success" => false,
                "errors" => $validator->getErrors(),
            ])->withStatus(400);
        }

        $helper = $helper->get($args['uuid']);
        if ($helper == NULL) {
            return $response->withJson([
                "success" => false,
                "errors" => ["Resource not found"],
            ])->withStatus(404);
        }

        foreach ($fields as $field => $fl) {
            $helper->offsetSet($field, $request->getParsedBody()[$field]); // FIXME: SECURITY: Sanitize input and block not allowed variables
        }

        if (!$helper->save()) {
            return $response->withJson([
                "success" => false,
            ])->withStatus(500);
        }

        return $response->withJson([
            "success" => true,
            "data" => $helper->jsonSerialize(),
        ]);
    }

    public function getElement(Helper $helper, Request $request, Response $response, array $args): Response|ResponseInterface
    {
        $helper = $helper->get($args['uuid']);
        if ($helper == NULL) {
            return $response->withJson([
                "success" => false,
                "errors" => ["Resource not found"],
            ])->withStatus(404);
        }

        return $response->withJson([
            "success" => true,
            "data" => $helper->jsonSerialize(),
        ]);
    }

    public function deleteElement(Helper $helper, Request $request, Response $response, array $args): Response|ResponseInterface
    {
        $helper = $helper->get($args['uuid']);
        if ($helper == NULL) {
            return $response->withJson([
                "success" => false,
                "errors" => ["Resource not found"],
            ])->withStatus(404);
        }

        if (!$helper->delete()) {
            return $response->withJson([
                "success" => false,
            ])->withStatus(500);
        }

        return $response->withJson([
            "success" => true,
        ]);
    }

    public abstract function list(Request $request, Response $response, array $args);

    public abstract function add(Request $request, Response $response, array $args);

    public abstract function details(Request $request, Response $response, array $args);

    public abstract function update(Request $request, Response $response, array $args);

    public abstract function delete(Request $request, Response $response, array $args);


}