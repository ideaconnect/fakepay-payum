<?php

namespace IDCT\Payum\Fakepay\Action;

use ArrayObject;
use IDCT\Payum\Fakepay\Request\Api\Sync;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject as SplArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Notify;
use Payum\Core\Request\GetHttpRequest;

class NotifyAction extends GatewayAwareAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request Notify */
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        $details = SplArrayObject::ensureArrayObject($request->getModel());

        $details['status'] = $httpRequest->request['status'];
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof Notify &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }
}
