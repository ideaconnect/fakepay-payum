<?php

namespace IDCT\Payum\Fakepay\Action;

use IDCT\Payum\Fakepay\Api;
use IDCT\Payum\Fakepay\Request\Api\DoPayment;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Request\Capture;

/**
 * CaptureAction
 */
class CaptureAction extends DoPaymentAction
{
    /**
     * @var Api
     */
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

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof Capture &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }
}
