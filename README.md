# chargily-epay-sylius
Sylius Plugin for Chargily ePay Gateway

![Chargily ePay Gateway](https://raw.githubusercontent.com/Chargily/epay-gateway-php/main/assets/banner-1544x500.png "Chargily ePay Gateway")

# Installation
1. Via Composer (Recomended)
```bash
composer require chargily/epay-sylius
```

2. Register the bundle, add this line at the end of the file config/bundles.php 
```bash
Chargily\EpayPlugin\ChargilyEpayPlugin::class => ['all' => true],
```

3. Import the services, Add the follow line in config/services.yml
```bash
imports:
    - { resource: "@ChargilyEpayPlugin/Resources/config/services.yml" }
```

4. Import the routes, Add the follow line in config/routes/sylius_shop.yml
```bash
sylius_shop_chargily:
    resource: "@ChargilyEpayPlugin/Resources/config/shop_routing.yml"
```
4. Clear the cache
```bash
php bin/console cache:clear
```

- Download the latest release
[Chargily Sylius Plugin](https://github.com/kiakahaDZ/chargily-epay-sylius/archive/refs/tags/v1.0.1.zip)

# Configurations
![Configurations Screenshot 1](https://raw.githubusercontent.com/kiakahaDZ/chargily-epay-sylius/main/assets/Screenshot_1.png "Chargily ePay Gateway")

![Configurations Screenshot 2](https://raw.githubusercontent.com/kiakahaDZ/chargily-epay-sylius/main/assets/Screenshot_2.png "Chargily ePay Gateway")

This Plugin is to integrate ePayment gateway with Chargily easily.
- Currently support payment by **CIB / EDAHABIA** cards and soon by **Visa / Mastercard** 
- This repo is recently created for **Sylius Plugin**, If you are a developer and want to collaborate to the development of this plugin, you are welcomed!

# Contribution tips
1. Make a fork of this repo.
2. Take a tour to our [API documentation here](https://dev.chargily.com/docs/#/epay_integration_via_api)
3. Get your API Key/Secret from [ePay by Chargily](https://epay.chargily.com.dz) dashboard for free.
4. Start developing.
5. Finished? Push and merge.
