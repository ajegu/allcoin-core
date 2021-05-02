<?php


namespace AllCoinCore\Lambda;


use AllCoinCore\Exception\LambdaInvokeException;
use AllCoinCore\Helper\SerializerHelper;
use AllCoinCore\Lambda\Event\LambdaEvent;
use Aws\Lambda\Exception\LambdaException;
use Aws\Lambda\LambdaClient;
use Psr\Log\LoggerInterface;

class LambdaAdapter
{
    const RequestResponse = 'RequestResponse';

    public function __construct(
        private LambdaClient $lambdaClient,
        private LoggerInterface $logger,
        private SerializerHelper $serializerHelper
    ) {}

    /**
     * @param LambdaEvent $event
     * @param string $functionName
     * @return array
     * @throws LambdaInvokeException
     */
    public function invoke(LambdaEvent $event, string $functionName): array
    {
        $payload = $this->serializerHelper->serialize($event);

        $args = [
            'FunctionName' => $functionName,
            'InvocationType' => self::RequestResponse,
            'Payload' => $payload,
        ];

        try {
            $result = $this->lambdaClient->invoke($args);
        } catch (LambdaException $exception) {
            $message = 'The lambda can not be invoked.';
            $this->logger->error($message, [
                'exception' => $exception->getMessage(),
                'lambda' => $functionName,
                'payload' => $payload
            ]);

            throw new LambdaInvokeException($message);
        }

        return json_decode($result->get('Payload'), true);
    }
}
