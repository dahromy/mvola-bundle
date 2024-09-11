<?php

namespace DahRomy\Mvola\Service\Callback;

interface MVolaCallbackHandlerInterface
{
    public function handleCallback(array $mvolaData, array $callbackData): void;
}