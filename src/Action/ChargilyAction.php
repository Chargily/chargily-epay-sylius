<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 12/4/18
 * Time: 12:32 PM
 */

namespace Chargily\EpayPlugin\Action;

use App\Entity\Order\Order;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Reply\HttpRedirect;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Chargily\ePay\Chargily;

final class ChargilyAction implements ApiAwareInterface, ActionInterface
{
    private $api = [];
    /**
     * @var OrderRepositoryInterface|null
     */
    private $orderRepository;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (!is_array($api)) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->api = $api;
    }

    public function __construct(?OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * {@inheritDoc}
     * @throws \Twig\Error\Error
     * @throws \Exception
     */
    public function execute($request)
    {
        $api_key = $this->api['api_key'];
        $api_secret = $this->api['api_secret'];
        $invoice_details = $this->api['invoice_details'];
        $base_url = $this->api['base_url'];
        $mode = $this->api['mode'];

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $number = $request->getFirstModel()->getId();
        //$number = $request->getFirstModel()['orderReference'];
        /** @var Order $order_origin */
        $order_origin = $this->orderRepository->findOneBy(['id' => $number]);
        //$order_origin = $this->orderRepository->findOneBy(['number' => $number]);

        $model['customer'] = $order_origin->getCustomer();
        $model['baseOrder'] = $order_origin;
        $model['locale'] = $this->getFallbackLocaleCode($order_origin->getLocaleCode());
        $model['phoneNumber'] = $order_origin->getShippingAddress()->getPhoneNumber();
        $model['orderReference'] = $order_origin->getNumber();

        $client_name = $order_origin->getBillingAddress()->getFirstName() . ' ' . $order_origin->getBillingAddress()->getLastName();

        $customer = $order_origin->getCustomer();

        if (($order_origin->getTotal() / 100) < 75) {
            //Redirect sylius_shop_homepage
            throw new HttpRedirect("{$base_url}");
            //throw new HttpRedirect("https://{$base_url}");
        }

        $totalCouponDiscount = 0;
        $dis = $order_origin->getAdjustmentsRecursively(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        foreach ($dis as $d) {
            $totalCouponDiscount = $totalCouponDiscount + $d->getAmount();
        }

        $chargily = new Chargily([
            //credentials
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            //urls
            'urls' => [
                'back_url' => "{$base_url}", // this is where client redirected after payment processing
                'webhook_url' => "{$base_url}chargily/response/{$order_origin->getNumber()}", // this is where you receive payment informations
            ],
            //mode
            'mode' => $mode, //OR CIB
            //payment details
            'payment' => [
                'number' => $order_origin->getNumber(), // Payment or order number
                'client_name' => $client_name, // Client name
                'client_email' => $customer->getEmail(), // This is where client receive payment receipt after confirmation
                'amount' => $order_origin->getTotal() / 100, //this the amount must be greater than or equal 75
                'discount' => $totalCouponDiscount / 100, //this is discount percentage between 0 and 99
                'description' => $invoice_details, // this is the payment description

            ]
        ]);
// get redirect url

        $redirectUrl = $chargily->getRedirectUrl();

        if ($redirectUrl) {
            //redirect
            throw new HttpRedirect($redirectUrl);
        } else {
            throw new HttpRedirect($base_url);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request->getModel() instanceof \ArrayObject;
    }

    private function getFallbackLocaleCode($localeCode)
    {
        return explode('_', $localeCode)[0];
    }
}
