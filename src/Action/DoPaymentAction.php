<?php

namespace IDCT\Payum\Fakepay\Action;

use Exception;
use IDCT\Payum\Fakepay\Action\Api\BaseApiAwareAction;
use IDCT\Payum\Fakepay\Request\Api\DoPayment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;

class DoPaymentAction extends BaseApiAwareAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request DoPayment */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->doPayment((array) $model, $request);
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
    protected function doPayment(array $fields, $request)
    {
        $fields['merchantId'] = $this->api->getApiOptions()['merchant_id'];
        $fields['merchantKey'] = $this->api->getApiOptions()['merchant_key'];

        $notifyToken = $this->tokenFactory->createNotifyToken(
            $request->getToken()->getGatewayName(),
            $request->getToken()->getDetails()
        );

        $fields['notifyUrl'] = $notifyToken->getTargetUrl();
        $fields['returnUrl'] = $request->getToken()->getAfterUrl();
        $fields['cancelUrl'] = $request->getToken()->getAfterUrl();

        $client = HttpClient::create(['http_version' => '1.1']);
        $formFields = $fields;

        unset($formFields['status']);
        $response = null;
        /** @var Response */
        $response = $client->request('POST', $this->api->getApiOptions()['endpoint'] . '/capture', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => $formFields
        ]);

        $data = json_decode($response->getContent(false), true);

        //magic for docker:
        $endpoint = str_replace('host.docker.internal', 'localhost', $this->api->getApiOptions()['endpoint']);
        throw new HttpRedirect($endpoint . $data['redirect_to']);
    }
}
