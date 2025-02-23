<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\MercanetBnpParibasPlugin;

use BitBag\MercanetBnpParibasPlugin\Action\ConvertPaymentAction;
use BitBag\MercanetBnpParibasPlugin\Bridge\MercanetBnpParibasBridgeInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class MercanetBnpParibasGatewayFactory extends GatewayFactory
{
    /**
     * @inheritDoc
     */
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'mercanet_bnp_paribas',
            'payum.factory_title' => 'Mercanet BNP Paribas',

            'payum.action.convert' => new ConvertPaymentAction(),

            'payum.http_client' => '@bitbag.mercanet_bnp_paribas.bridge.mercanet_bnp_paribas_bridge',
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => '',
                'secure_key' => '',
                'merchant_id' => '',
                'key_version' => '',
            ];

            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['secret_key', 'environment', 'merchant_id', 'key_version'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                /** @var MercanetBnpParibasBridgeInterface $mercanetBnpParibasBridge */
                $mercanetBnpParibasBridge = $config['payum.http_client'];

                $mercanetBnpParibasBridge->setSecretKey($config['secret_key']);
                $mercanetBnpParibasBridge->setMerchantId($config['merchant_id']);
                $mercanetBnpParibasBridge->setKeyVersion($config['key_version']);
                $mercanetBnpParibasBridge->setEnvironment($config['environment']);

                return $mercanetBnpParibasBridge;
            };
        }
    }
}
