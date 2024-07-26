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

namespace BitBag\MercanetBnpParibasPlugin\Legacy;

use Payum\Core\Reply\HttpResponse;

final class SimplePayment
{
    private Mercanet $mercanet;

    private string $environment;

    private string $merchantId;

    private string $keyVersion;

    private int $amount;

    private string $currency;

    private string $transactionReference;

    private string $automaticResponseUrl;

    private string $targetUrl;

    public function __construct(
        Mercanet $mercanet,
        string $merchantId,
        string $keyVersion,
        string $environment,
        int $amount,
        string $targetUrl,
        string $currency,
        string $transactionReference,
        string $automaticResponseUrl,
    ) {
        $this->automaticResponseUrl = $automaticResponseUrl;
        $this->transactionReference = $transactionReference;
        $this->mercanet = $mercanet;
        $this->environment = $environment;
        $this->merchantId = $merchantId;
        $this->keyVersion = $keyVersion;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->targetUrl = $targetUrl;
    }

    public function execute(): void
    {
        $this->resolveEnvironment();

        $this->mercanet->setMerchantId($this->merchantId);
        $this->mercanet->setInterfaceVersion(Mercanet::INTERFACE_VERSION);
        $this->mercanet->setKeyVersion($this->keyVersion);
        $this->mercanet->setAmount($this->amount);
        $this->mercanet->setCurrency($this->currency);
        $this->mercanet->setOrderChannel('INTERNET');
        $this->mercanet->setTransactionReference($this->transactionReference);
        $this->mercanet->setNormalReturnUrl($this->targetUrl);
        $this->mercanet->setAutomaticResponseUrl($this->automaticResponseUrl);

        $this->mercanet->validate();

        /** @var string $response */
        $response = $this->mercanet->executeRequest();

        throw new HttpResponse($response);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function resolveEnvironment(): void
    {
        if (Mercanet::TEST === $this->environment) {
            $this->mercanet->setUrl(Mercanet::TEST);

            return;
        }

        if (Mercanet::PRODUCTION === $this->environment) {
            $this->mercanet->setUrl(Mercanet::PRODUCTION);

            return;
        }

        if (Mercanet::SIMULATION === $this->environment) {
            $this->mercanet->setUrl(Mercanet::SIMULATION);

            return;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'The "%s" environment is invalid. Expected %s or %s',
                $this->environment,
                Mercanet::PRODUCTION,
                Mercanet::TEST,
            ),
        );
    }
}
