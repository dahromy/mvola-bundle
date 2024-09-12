<?php

namespace DahRomy\MVola\Service\Callback;

interface MVolaCallbackHandlerInterface
{
    public function handleCallback(array $mvolaData, array $callbackData): void;
}