<?php

namespace DahRomy\MVola\Service\Auth;

interface MVolaAuthenticatorInterface
{
    public function authenticate(): array;
}