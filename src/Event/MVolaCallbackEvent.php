<?php

namespace DahRomy\Mvola\Event;

use Symfony\Contracts\EventDispatcher\Event;

class MVolaCallbackEvent extends Event
{
    public const NAME = 'mvola.callback';

    private array $mvolaData;
    private array $callbackData;

    public function __construct(array $mvolaData, array $callbackData)
    {
        $this->mvolaData = $mvolaData;
        $this->callbackData = $callbackData;
    }

    public function getMVolaData(): array
    {
        return $this->mvolaData;
    }

    public function getCallbackData(): array
    {
        return $this->callbackData;
    }
}