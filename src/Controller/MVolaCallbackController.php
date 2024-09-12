<?php

namespace DahRomy\MVola\Controller;

use DahRomy\MVola\Service\Callback\MVolaCallbackHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MVolaCallbackController extends AbstractController
{
    private MVolaCallbackHandlerInterface $callbackHandler;

    public function __construct(MVolaCallbackHandlerInterface $callbackHandler)
    {
        $this->callbackHandler = $callbackHandler;
    }

    public function handleCallback(Request $request): Response
    {
        $mvolaData = json_decode($request->getContent(), true);
        $encodedData = $request->query->get('data', '');
        $callbackData = json_decode(base64_decode($encodedData), true);

        $this->callbackHandler->handleCallback($mvolaData, $callbackData);

        return new Response('Callback received', Response::HTTP_OK);
    }
}