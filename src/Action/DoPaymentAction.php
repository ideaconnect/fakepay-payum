<?php

namespace IDCT\Payum\Fakepay\Action;

use IDCT\Payum\Fakepay\Action\Api\BaseApiAwareAction;
use IDCT\Payum\Fakepay\Request\Api\DoPayment;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpPostRedirect;

class DoPaymentAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request DoPayment */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->doPayment((array) $model);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof DoPayment &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }

    /**
     * @param array $fields
     */
    protected function doPayment(array $fields)
    {
        $fields['merchant_id'] = $this->api->getApiOptions()['merchant_id'];
        $fields['merchant_key'] = $this->api->getApiOptions()['merchant_key'];
        $fields['notify_url'] = $this->api->getApiOptions()['notify_url'];
        $fields['return_url'] = $this->api->getApiOptions()['return_url'];
        $fields['cancel_url'] = $this->api->getApiOptions()['cancel_url'];

        throw new HttpPostRedirect($this->api->getApiOptions()['endpoint'], $fields);
    }
}
