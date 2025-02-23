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

namespace Tests\BitBag\MercanetBnpParibasPlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    /**
     * @param string $secretKey
     */
    public function setMercanetBnpParibasPluginGatewaySecretKey($secretKey);

    /**
     * @param string $merchantId
     */
    public function setMercanetBnpParibasPluginGatewayMerchantId($merchantId);

    /**
     * @param string $keyVersion
     */
    public function setMercanetBnpParibasPluginGatewayKeyVersion($keyVersion);

    /**
     * @param string $environment
     */
    public function setMercanetBnpParibasPluginGatewayEnvironment($environment);

    /**
     * @param string $message
     *
     * @return bool
     */
    public function findValidationMessage($message);
}
