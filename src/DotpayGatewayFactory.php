<?php

namespace IDCT\Payum\Fakepay;

use IDCT\Payum\Fakepay\Action\CaptureAction;
use IDCT\Payum\Fakepay\Action\ConvertPaymentAction;
use IDCT\Payum\Fakepay\Action\DoPaymentAction;
use IDCT\Payum\Fakepay\Action\NotifyAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class FakePayGatewayFactory extends GatewayFactory
{
    /**
     *
     * @param ArrayObject $config
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'fakepay',
            'payum.factory_title' => 'Fakepay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.api.do_payment' => new DoPaymentAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'merchant_id' => '',
                'merchant_key' => '',
                'endpoint' => Api::DEFAULT_ENDPOINT,
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['merchant_id', 'merchant_key'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $config = [
                    'merchant_id' => $config['merchant_id'],
                    'merchant_key' => $config['merchant_key'],
                    'endpoint' => $config['endpoint'],
                ];

                return new Api($config, $config['payum.http_client']);
            };
        }
    }
}
