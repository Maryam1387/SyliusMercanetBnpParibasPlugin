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

namespace BitBag\MercanetBnpParibasPlugin\Action;

use BitBag\MercanetBnpParibasPlugin\Bridge\MercanetBnpParibasBridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Notify;
use SM\Factory\FactoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Webmozart\Assert\Assert;

final class NotifyAction implements ActionInterface, ApiAwareInterface
{
    use GatewayAwareTrait;

    private MercanetBnpParibasBridgeInterface $mercanetBnpParibasBridge;

    private FactoryInterface $stateMachineFactory;

    public function __construct(FactoryInterface $stateMachineFactory)
    {
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * @param $request Notify
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        if ($this->mercanetBnpParibasBridge->paymentVerification()) {
            /** @var PaymentInterface $payment */
            $payment = $request->getFirstModel();

            $payment->getDetails()['authorisationId'] = $this->mercanetBnpParibasBridge->getAuthorisationId();

            Assert::isInstanceOf($payment, PaymentInterface::class);

            $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH)->apply(PaymentTransitions::TRANSITION_COMPLETE);
        }
    }

    public function setApi($mercanetBnpParibasBridge): void
    {
        if (!$mercanetBnpParibasBridge instanceof MercanetBnpParibasBridgeInterface) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->mercanetBnpParibasBridge = $mercanetBnpParibasBridge;
    }

    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayObject
        ;
    }
}
