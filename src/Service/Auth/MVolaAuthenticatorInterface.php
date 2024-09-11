<?php

namespace DahRomy\Mvola\Service\Auth;

interface MVolaAuthenticatorInterface
{
    public function authenticate(): array;
}