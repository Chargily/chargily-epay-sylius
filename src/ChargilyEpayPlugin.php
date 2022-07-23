<?php

declare(strict_types=1);

namespace Chargily\EpayPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ChargilyEpayPlugin extends Bundle
{
    use SyliusPluginTrait;
}
