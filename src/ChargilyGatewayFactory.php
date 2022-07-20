<?php
/**
 * Created by PhpStorm.
 * User: hamad
 * Date: 3/19/18
 * Time: 8:53 AM
 */




namespace Kiakaha\ChargilyPlugin;
use Kiakaha\ChargilyPlugin\Action\ChargilyAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class ChargilyGatewayFactory extends GatewayFactory
{

    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'chargily',
            'payum.factory_title' => 'chargily',
            'payum.action.capture' => new ChargilyAction(null)
            ]);
        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'api_key' => '',
                'api_secret' => '',
                'invoice_details' => '',
                'base_url' => '',
                'mode'=>''
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [ 'api_key', 'api_secret', 'invoice_details', 'base_url', 'mode'];
            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);
                return [
                    'api_key'=> $config['api_key'],
                    'api_secret' => $config['api_secret'],
                    'invoice_details' => $config['invoice_details'],
                    'base_url' => $config['base_url'],
                    'mode' => $config['mode']
                ];
            };
        }
    }
}
