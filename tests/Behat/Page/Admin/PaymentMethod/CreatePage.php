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

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    /**
     * @inheritdoc
     */
    public function setMercanetBnpParibasPluginGatewaySecretKey($secretKey)
    {
        $this->getDocument()->fillField('Secure key', $secretKey);
    }

    /**
     * @inheritdoc
     */
    public function setMercanetBnpParibasPluginGatewayMerchantId($merchantId)
    {
        $this->getDocument()->fillField('Merchant ID', $merchantId);
    }

    /**
     * @inheritdoc
     */
    public function setMercanetBnpParibasPluginGatewayKeyVersion($keyVersion)
    {
        $this->getDocument()->fillField('Key version', $keyVersion);
    }

    /**
     * @inheritdoc
     */
    public function setMercanetBnpParibasPluginGatewayEnvironment($environment)
    {
        $this->getDocument()->selectFieldOption('Environment', $environment);
    }

    /**
     * @inheritdoc
     */
    public function findValidationMessage($message)
    {
        $elements = $this->getDocument()->findAll('css', '.sylius-validation-error');

        /** @var NodeElement $element */
        foreach ($elements as $element) {
            if ($element->getText() === $message) {
                return true;
            }
        }

        return false;
    }
}
