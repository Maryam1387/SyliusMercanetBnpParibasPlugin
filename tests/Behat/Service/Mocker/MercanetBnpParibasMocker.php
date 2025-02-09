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

namespace Tests\BitBag\MercanetBnpParibasPlugin\Behat\Service\Mocker;

use BitBag\MercanetBnpParibasPlugin\Bridge\MercanetBnpParibasBridgeInterface;
use BitBag\MercanetBnpParibasPlugin\Legacy\Mercanet;
use Sylius\Behat\Service\Mocker\Mocker;

final class MercanetBnpParibasMocker
{
    /** @var Mocker */
    private $mocker;

    public function __construct(Mocker $mocker)
    {
        $this->mocker = $mocker;
    }

    public function completedPayment(callable $action)
    {
        $openMercanetBnpParibasWrapper = $this->mocker
            ->mockService('bitbag.mercanet_bnp_paribas.bridge.mercanet_bnp_paribas_bridge', MercanetBnpParibasBridgeInterface::class);

        $openMercanetBnpParibasWrapper
            ->shouldReceive('createMercanet')
            ->andReturn(new Mercanet('test'));

        $openMercanetBnpParibasWrapper
            ->shouldReceive('paymentVerification')
            ->andReturn(true);

        $openMercanetBnpParibasWrapper
            ->shouldReceive('isPostMethod')
            ->andReturn(true);

        $openMercanetBnpParibasWrapper
            ->shouldReceive('setSecretKey', 'setEnvironment', 'setMerchantId', 'setKeyVersion')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getSecretKey')
            ->andReturn('test')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getMerchantId')
            ->andReturn('test')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getKeyVersion')
            ->andReturn('test')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getEnvironment')
            ->andReturn(Mercanet::TEST)
        ;

        $openMercanetBnpParibasWrapper->shouldReceive('getAuthorisationId')->andReturn('12345');

        $action();

        $this->mocker->unmockAll();
    }

    public function canceledPayment(callable $action)
    {
        $openMercanetBnpParibasWrapper = $this->mocker
            ->mockService('bitbag.mercanet_bnp_paribas.bridge.mercanet_bnp_paribas_bridge', MercanetBnpParibasBridgeInterface::class);

        $openMercanetBnpParibasWrapper
            ->shouldReceive('createMercanet')
            ->andReturn(new Mercanet('test'));

        $openMercanetBnpParibasWrapper
            ->shouldReceive('paymentVerification')
            ->andReturn(false);

        $openMercanetBnpParibasWrapper
            ->shouldReceive('isPostMethod')
            ->andReturn(true);

        $openMercanetBnpParibasWrapper
            ->shouldReceive('setSecretKey', 'setEnvironment', 'setMerchantId', 'setKeyVersion')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getSecretKey')
            ->andReturn('test')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getMerchantId')
            ->andReturn('test')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getKeyVersion')
            ->andReturn('test')
        ;

        $openMercanetBnpParibasWrapper
            ->shouldReceive('getEnvironment')
            ->andReturn(Mercanet::TEST)
        ;

        $openMercanetBnpParibasWrapper->shouldReceive('getAuthorisationId')->andReturn('12345');

        $action();

        $this->mocker->unmockAll();
    }
}
