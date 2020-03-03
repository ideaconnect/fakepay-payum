<?php

namespace IDCT\Payum\Fakepay\Action;

use IDCT\Payum\Fakepay\Constants;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request GetStatusInterface */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (false == $model['status'] || $model['status'] == Constants::STATUS_NEW) {
            $request->markNew();

            return;
        }

        if (Constants::STATUS_PENDING == $model['status']) {
            $request->markPending();

            return;
        }

        if (Constants::STATUS_CAPTURED == $model['status']) {
            $request->markCaptured();

            return;
        }

        if (Constants::STATUS_AUTHORIZED == $model['status']) {
            $request->markAuthorized();

            return;
        }

        if (Constants::STATUS_FAILED == $model['status']) {
            $request->markFailed();

            return;
        }

        if (Constants::STATUS_REFUNDED == $model['status']) {
            $request->markRefunded();

            return;
        }

        if (Constants::STATUS_COMPLAINT == $model['status'] || Constants::STATUS_CANCELED == $model['status']) {
            $request->markCanceled();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof GetStatusInterface &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }
}
