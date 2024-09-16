# [![](https://bitbag.io/wp-content/uploads/2020/10/mercanet_bnp_paribas-1024x535.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mercanet)

# Mercanet BNP Paribas Plugin for Sylius
----

[![](https://img.shields.io/packagist/l/bitbag/mercanet-bnp-paribas-plugin.svg) ](https://packagist.org/packages/bitbag/mercanet-bnp-paribas-plugin "License") [ ![](https://img.shields.io/packagist/v/bitbag/mercanet-bnp-paribas-plugin.svg) ](https://packagist.org/packages/bitbag/mercanet-bnp-paribas-plugin "Version") [ ![](https://img.shields.io/travis/BitBagCommerce/SyliusMercanetBnpParibasPlugin/master.svg) ](http://travis-ci.org/BitBagCommerce/SyliusMercanetBnpParibasPlugin "Build status") [![](https://poser.pugx.org/bitbag/mercanet-bnp-paribas-plugin/downloads)](https://packagist.org/packages/bitbag/mercanet-bnp-paribas-plugin "Total Downloads") [![Slack](https://img.shields.io/badge/community%20chat-slack-FF1493.svg)](http://sylius-devs.slack.com) [![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mercanet)

We want to impact many unique eCommerce projects and build our brand recognition worldwide, so we are heavily involved in creating open-source solutions, especially for Sylius. We have already created **over 35 extensions, which have been downloaded almost 2 million times.**

You can find more information about our eCommerce services and technologies on our website: https://bitbag.io/. We have also created a unique service dedicated to creating plugins: https://bitbag.io/services/sylius-plugin-development. 

Do you like our work? Would you like to join us? Check out the **“Career” tab**: https://bitbag.io/pl/kariera.

# About Us 

BitBag is a software house that implements tailor-made eCommerce platforms with the entire infrastructure—from creating eCommerce platforms to implementing PIM and CMS systems to developing custom eCommerce applications, specialist B2B solutions, and migrations from other platforms.

We actively participate in Sylius's development. We have already completed **over 150 projects**, cooperating with clients from all over the world, including smaller enterprises and large international companies. We have completed projects for such important brands as **Mytheresa, Foodspring, Planeta Huerto (Carrefour Group), Albeco, Mollie, and ArtNight.**

We have a 70-person team of experts: business analysts and eCommerce consultants, developers, project managers, and QA testers.

**Our services:**
* B2B and B2C eCommerce platform implementations
* Multi-vendor marketplace platform implementations
* eCommerce migrations
* Sylius plugin development
* Sylius consulting
* Project maintenance and long-term support
* PIM and CMS implementations

**Some numbers from BitBag regarding Sylius:**
* 70 experts on board 
* +150 projects delivered on top of Sylius
* 30 countries of BitBag’s customers
* 7 years in the Sylius ecosystem
* +35 plugins created for Sylius
  
---

   [![](https://bitbag.io/wp-content/uploads/2024/09/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_adyen)

---


## Table of Content
---
* [Overview](#overview)
* [Functionalities](doc/functionalities.md)
* [Installation](#installation)
  * [Requirements](#requirements)
* [Testing](#testing)
* [Usage](#usage)
* [Demo](#demo)
* [Additional resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact and Support](#contact-and-support)
* [Community](#community)

# Overview
---
Unlock seamless payment processing with the BNPParibasPayments Plugin for Sylius. This open-source marvel is your key to effortlessly integrating the Mercanet BNP Paribas payment system into your Sylius platform app. We've got your back every step of the way.

# Installation
----
## Requirements
----
We work on stable, supported, and up-to-date versions of packages. We recommend you do the same.

| Package | Version | Version |
| --- |-------|-------|
| PHP | ^8.0  | ^8.1  |
| Sylius | ^1.12 | ^1.13 |

----

```bash
$ composer require bitbag/mercanet-bnp-paribas-plugin
```
    
Add plugin dependencies to your AppKernel.php file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        
        new \BitBag\MercanetBnpParibasPlugin\BitBagMercanetBnpParibasPlugin(),
    ]);
}
```

## Testing
----

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
$ yarn install
$ yarn run gulp
$ php bin/console sylius:install --env test
$ php bin/console server:start --env test
$ open http://localhost:8000
$ bin/behat features/*
$ bin/phpspec run
```
## Usage
----

Go to the payment methods in your admin panel. Now you should be able to add new payment method for Mercanet BNP Paribas gateway.

# Demo
---

We created a demo app with some useful use-cases of plugins! Visit http://demo.sylius.com/ to take a look at it.

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mercanet)

# Additional resources for developers
---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)
* [Sylius Plugins Blogs](https://bitbag.io/blog/category/plugins)

# License
 ---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

# Contact and Support
---
This open-source plugin was developed to help the Sylius community. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know! **Contact us** or send us an **e-mail to hello@bitbag.io** with your question(s).

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mercanet)


# Community
---

For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

[![](https://bitbag.io/wp-content/uploads/2024/09/badges-partners.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mercanet)
