<?php

namespace DahRomy\MVola\Service\Callback;

use DahRomy\MVola\Event\MVolaCallbackEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MVolaCallbackHandler implements MVolaCallbackHandlerInterface
{
    private LoggerInterface $logger;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(LoggerInterface $logger, EventDispatcherInterface $eventDispatcher)
    {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handleCallback(array $mvolaData, array $callbackData): void
    {
        $context = [
            'mvolaData' => $mvolaData,
            'callbackData' => $callbackData,
        ];

        $this->logger->info('MVola callback received', $context);

        try {
            $event = new MVolaCallbackEvent($mvolaData, $callbackData);
            $this->eventDispatcher->dispatch($event, MVolaCallbackEvent::NAME);
            $this->logger->info('MVola callback event dispatched successfully', $context);
        } catch (\Exception $e) {
            $this->logger->error('Error dispatching MVola callback event: ' . $e->getMessage(), array_merge($context, ['exception' => $e]));
        }
    }
}
