<?php

namespace IDCT\Payum\Fakepay\Action\Api;

use IDCT\Payum\Fakepay\Api;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\UnsupportedApiException;

abstract class BaseApiAwareAction implements ActionInterface, ApiAwareInterface
{
    protected $api;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->api = $api;
    }
}
