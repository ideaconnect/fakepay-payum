<?php

namespace IDCT\Payum\Fakepay\Action;

use IDCT\Payum\Fakepay\Request\Api\Sync;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHttpRequest;

class SyncAction extends GatewayAwareAction implements ActionInterface
{
    /**
     *
     * @var ArrayObject Api config
     */
    protected $config;

    /**
     *
     * @param ArrayObject $config
     */
    public function __construct(ArrayObject $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request Sync */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($httpRequest = new GetHttpRequest());
        $requestData = $httpRequest->request;

        $model = $requestData->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if (false == $request instanceof Sync) {
            return false;
        }

        $model = $request->getModel();
        if (false == $model instanceof \ArrayAccess) {
            return false;
        }

        return true;
    }
}
