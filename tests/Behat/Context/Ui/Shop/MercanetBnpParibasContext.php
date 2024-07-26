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

namespace Tests\BitBag\MercanetBnpParibasPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\BitBag\MercanetBnpParibasPlugin\Behat\Page\External\MercanetBnpParibasCheckoutPageInterface;
use Tests\BitBag\MercanetBnpParibasPlugin\Behat\Service\Mocker\MercanetBnpParibasMocker;

final class MercanetBnpParibasContext implements Context
{
    /** @var MercanetBnpParibasMocker */
    private $mercanetBnpParibasMocker;

    /** @var CompletePageInterface */
    private $summaryPage;

    /** @var MercanetBnpParibasCheckoutPageInterface */
    private $mercanetBnpParibasCheckoutPage;

    /** @var ShowPageInterface */
    private $orderDetails;

    public function __construct(
        MercanetBnpParibasMocker $mercanetBnpParibasMocker,
        CompletePageInterface $summaryPage,
        MercanetBnpParibasCheckoutPageInterface $mercanetBnpParibasCheckoutPage,
        ShowPageInterface $orderDetails,
    ) {
        $this->orderDetails = $orderDetails;
        $this->mercanetBnpParibasCheckoutPage = $mercanetBnpParibasCheckoutPage;
        $this->summaryPage = $summaryPage;
        $this->mercanetBnpParibasMocker = $mercanetBnpParibasMocker;
    }

    /**
     * @Given I have confirmed my order with Mercanet Bnp Paribas payment
     * @When I confirm my order with Mercanet Bnp Paribas payment
     */
    public function iConfirmMyOrderWithMercanetBnpParibasPayment()
    {
        $this->summaryPage->confirmOrder();
    }

    /**
     * @When I sign in to Mercanet Bnp Paribas and pay successfully
     */
    public function iSignInToMercanetBnpParibasAndPaySuccessfully()
    {
        $this->mercanetBnpParibasMocker->completedPayment(function () {
            $this->mercanetBnpParibasCheckoutPage->pay();
        });
    }

    /**
     * @Given I have cancelled Mercanet Bnp Paribas payment
     * @When I cancel my Mercanet Bnp Paribas payment
     */
    public function iCancelMyMercanetBnpParibasPayment()
    {
        $this->mercanetBnpParibasMocker->canceledPayment(function () {
            $this->mercanetBnpParibasCheckoutPage->cancel();
        });
    }

    /**
     * @When I try to pay again Mercanet Bnp Paribas payment
     */
    public function iTryToPayAgainMercanetBnpParibasPayment()
    {
        $this->mercanetBnpParibasMocker->completedPayment(function () {
            $this->orderDetails->pay();
        });
    }
}
