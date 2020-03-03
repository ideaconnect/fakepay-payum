<?php

namespace IDCT\Payum\Fakepay\Action;

use IDCT\Payum\Fakepay\Constants;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;

class ConvertPaymentAction implements ActionInterface
{
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $this->validateCurrency($payment->getCurrencyCode());

        $details['amount'] = $payment->getTotalAmount();
        $details['currency'] = strtoupper($payment->getCurrencyCode());
        $details['description'] = $payment->getDescription();
        $details['clientEmail'] = $payment->getClientEmail();
        $details['paymentId'] = $payment->getNumber();
        $details['status'] = Constants::STATUS_CAPTURED;

        $request->setResult((array) $details);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof Convert &&
                $request->getSource() instanceof PaymentInterface &&
                $request->getTo() == 'array'
        ;
    }

    /**
     *
     * @param string $currency
     * @throws InvalidArgumentException
     */
    protected function validateCurrency($currency)
    {
        if (!in_array(strtoupper($currency), Constants::getSupportedCurrencies())) {
            throw new InvalidArgumentException("Currency $currency is not supported.", 400);
        }
    }
}
