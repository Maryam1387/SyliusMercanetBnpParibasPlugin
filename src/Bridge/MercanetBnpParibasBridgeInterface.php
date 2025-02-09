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

interface MercanetBnpParibasBridgeInterface
{
    /** @phpstan-ignore-next-line We should not change our business logic now*/
    public function createMercanet(string $secretKey): Mercanet;

    public function paymentVerification(): bool;

    public function getAuthorisationId(): string;

    public function isPostMethod(): bool;

    public function getSecretKey(): string;

    public function setSecretKey(string $secretKey): void;

    public function getMerchantId(): string;

    public function setMerchantId(string $merchantId): void;

    public function getKeyVersion(): string;

    public function setKeyVersion(string $keyVersion): void;

    public function getEnvironment(): string;

    public function setEnvironment(string $environment): void;
}
