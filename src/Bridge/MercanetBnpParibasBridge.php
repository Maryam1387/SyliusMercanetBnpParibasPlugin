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

namespace BitBag\MercanetBnpParibasPlugin\Bridge;

use BitBag\MercanetBnpParibasPlugin\Legacy\Mercanet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class MercanetBnpParibasBridge implements MercanetBnpParibasBridgeInterface
{
    private RequestStack $requestStack;

    private string $secretKey;

    private string $merchantId;

    private string $keyVersion;

    private string $environment;

    private Mercanet $mercanet;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /** @phpstan-ignore-next-line We should not change our business logic now*/
    public function createMercanet(string $secretKey): Mercanet
    {
        return new Mercanet($secretKey);
    }

    public function paymentVerification(): bool
    {
        if ($this->isPostMethod()) {
            $this->mercanet = new Mercanet($this->secretKey);
            $this->mercanet->setResponse($_POST);

            return $this->mercanet->isValid();
        }

        return false;
    }

    public function getAuthorisationId(): string
    {
        /** @phpstan-ignore-next-line This method does not exist, but we should not change that now*/
        return $this->mercanet->getAuthorisationId();
    }

    public function isPostMethod(): bool
    {
        /** @var Request $currentRequest */
        $currentRequest = $this->requestStack->getCurrentRequest();

        return $currentRequest->isMethod('POST');
    }

    /** @phpstan-ignore-next-line We should not change our business logic now*/
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /** @phpstan-ignore-next-line We should not change our business logic now*/
    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function setMerchantId(string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getKeyVersion(): string
    {
        return $this->keyVersion;
    }

    public function setKeyVersion(string $keyVersion): void
    {
        $this->keyVersion = $keyVersion;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function setEnvironment(string $environment): void
    {
        $this->environment = $environment;
    }
}
