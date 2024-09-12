# MVola Bundle for Symfony

The MVola Bundle is a Symfony bundle that provides integration with the MVola payment gateway. It offers a convenient way to interact with the MVola API, handle transactions, and manage callbacks.

## Features

- Easy integration with Symfony projects
- Handles MVola API authentication
- Provides services for initiating transactions, checking transaction status, and retrieving transaction details
- Includes a callback handler for processing MVola notifications
- Configurable retry mechanism for API calls
- Caching support for authentication tokens

## Installation

You can install the MVola Bundle using Composer:

```bash
composer require dahromy/mvola-bundle
```

## Configuration

After installing the bundle, you need to configure it in your Symfony application. Add the following to your `config/packages/mvola.yaml` file:

```yaml
mvola:
    environment: '%env(MVOLA_ENVIRONMENT)%'
    merchant_number: '%env(MVOLA_MERCHANT_NUMBER)%'
    company_name: '%env(MVOLA_COMPANY_NAME)%'
    consumer_key: '%env(MVOLA_CONSUMER_KEY)%'
    consumer_secret: '%env(MVOLA_CONSUMER_SECRET)%'
    auth_url: '%env(MVOLA_AUTH_URL)%'
    max_retries: 3
    retry_delay: 1000
    cache_ttl: 3600
```

Make sure to set the corresponding environment variables in your `.env` file:

```
MVOLA_ENVIRONMENT=sandbox
MVOLA_CONSUMER_KEY=your_consumer_key_here
MVOLA_CONSUMER_SECRET=your_consumer_secret_here
MVOLA_MERCHANT_NUMBER=your_merchant_number_here
MVOLA_COMPANY_NAME=your_company_name_here
MVOLA_AUTH_URL=https://sandbox.mvola.mg/token
```

## Usage

### Initiating a Transaction

To initiate a transaction, you can use the `MVolaService`:

```php
use DahRomy\MVola\Service\MVolaService;
use DahRomy\MVola\Model\TransactionRequest;

class PaymentController extends AbstractController
{
    private $mvolaService;

    public function __construct(MVolaService $mvolaService)
    {
        $this->mvolaService = $mvolaService;
    }

    public function initiatePayment()
    {
        $transactionRequest = new TransactionRequest();
        $transactionRequest->setAmount(1000)
            ->setCurrency('Ar')
            ->setDescriptionText('Payment for order #123')
            ->setRequestingOrganisationTransactionReference('123456')
            ->setRequestDate(new \DateTime())
            ->setOriginalTransactionReference('123456')
            ->setDebitParty([['key' => 'msisdn', 'value' => '0343500003']])
            ->setCreditParty([['key' => 'msisdn', 'value' => '0343500004']])
            ->setMetadata([
                ['key' => 'partnerName', 'value' => 'Partner Name'],
                ['key' => 'fc', 'value' => 'USD'],
                ['key' => 'amountFc', 'value' => '1']
            ])
            ->setCallbackData([
                'userId' => '123456',
                // ... other callback data
            ]);

        $result = $this->mvolaService->initiateTransaction($transactionRequest);

        // Handle the result
    }
}
```

### Checking Transaction Status

To check the status of a transaction:

```php
$status = $this->mvolaService->getTransactionStatus($serverCorrelationId);
```

### Retrieving Transaction Details

To get the details of a transaction:

```php
$details = $this->mvolaService->getTransactionDetails($transactionId);
```


### Handling Callbacks

The MVola Bundle provides a built-in callback handler that processes incoming callbacks from MVola. To handle these callbacks in your application, follow these steps:

1. Create an event subscriber:

```php
use DahRomy\MVola\Event\MVolaCallbackEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MVolaCallbackSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MVolaCallbackEvent::NAME => 'onMVolaCallback',
        ];
    }

    public function onMVolaCallback(MVolaCallbackEvent $event): void
    {
        // Response data from MVola
        $mvolaData = $event->getMVolaData();
        
        // Callback data sent with the transaction request
        $callbackData = $event->getCallbackData();

        // Process the callback data
        // For example, update the transaction status in your database
        // or trigger any necessary business logic
    }
}
```

2. Register your event subscriber in your `services.yaml`:

```yaml
services:
    App\EventSubscriber\MVolaCallbackSubscriber:
        tags:
            - { name: kernel.event_subscriber }
```

3. Configure the callback URL:

When initiating a transaction, you can include custom callback data:

```php
$transactionRequest = new TransactionRequest();
// ... set other transaction details ...
$transactionRequest->setCallbackData([
    'orderId' => '123456',
    'customerId' => '789',
]);

$result = $this->mvolaService->initiateTransaction($transactionRequest);
```

The MVola Bundle will automatically handle incoming callbacks at the `/mvola/callback` endpoint. When a callback is received, it will:

1. Log the received callback data
2. Dispatch a `MVolaCallbackEvent`

Your event subscriber will then be called to process the callback data according to your application's needs.

You can also customize the callback URL by setting the `callbackUrl` property in the `TransactionRequest` object:

```php
$transactionRequest->setCallbackUrl('https://example.com/mvola/callback');
```

Note: Ensure that your server is configured to accept PUT requests at the callback URL, as MVola sends callbacks using the PUT method.

## Error Handling

The bundle throws specific exceptions for different error scenarios. Make sure to catch and handle these exceptions in your application:

- `MVolaApiException`: For general API errors
- `MVolaAuthenticationException`: For authentication-related errors
- `MVolaValidationException`: For validation errors in the request data
- `MVolaNetworkException`: For network-related errors
- `MVolaRateLimitException`: For rate limit errors

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This bundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
