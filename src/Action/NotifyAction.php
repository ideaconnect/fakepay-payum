<?php

namespace IDCT\Payum\Fakepay\Action;

use IDCT\Payum\Fakepay\Request\Api\Sync;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Notify;

class NotifyAction extends GatewayAwareAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request Notify */
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute(new Sync($request->getModel()));
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
