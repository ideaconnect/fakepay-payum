<?php

namespace IDCT\Payum\Fakepay;

use Payum\Core\Bridge\Guzzle\HttpClientFactory;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\HttpClientInterface;

class Api
{
    const DEFAULT_ENDPOINT = 'http://localhost:8000/';

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @param array $options
     * @param HttpClientInterface|null $client
     */
    public function __construct(array $options, HttpClientInterface $client = null)
    {
        $options = ArrayObject::ensureArrayObject($options);
        $options->defaults($this->options);
        $options->validateNotEmpty([
            'merchant_id', 'merchant_key'
        ]);

        $this->options = $options;
        $this->client = $client ? : HttpClientFactory::create();
    }

    /**
     *
     * @return array
     */
    public function getApiOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    protected function getApiEndpoint()
    {
        return $this->options['endpoint'];
    }
}
