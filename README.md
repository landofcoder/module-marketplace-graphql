# Mage2 Module Lof MarketplaceGraphQl

    ``lof/module-MarketplaceGraphQl``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
magento 2 product label graphql extension

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Lof`
 - Enable the module by running `php bin/magento module:enable Lof_MarketplaceGraphQl`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require lof/module-MarketplaceGraphQl`
 - enable the module by running `php bin/magento module:enable Lof_MarketplaceGraphQl`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications

 - Model
	- Label

 - API Endpoint
	- GET - Lof\MarketplaceGraphQl\Api\Lof_labelsManagementInterface > Lof\MarketplaceGraphQl\Model\Lof_labelsManagement

 - API Endpoint
	- POST - Lof\MarketplaceGraphQl\Api\Lof_labelsManagementInterface > Lof\MarketplaceGraphQl\Model\Lof_labelsManagement

 - API Endpoint
	- PUT - Lof\MarketplaceGraphQl\Api\Lof_labelsManagementInterface > Lof\MarketplaceGraphQl\Model\Lof_labelsManagement

 - API Endpoint
	- GET - Lof\MarketplaceGraphQl\Api\Lof_labelManagementInterface > Lof\MarketplaceGraphQl\Model\Lof_labelManagement

 - API Endpoint
	- DELETE - Lof\MarketplaceGraphQl\Api\Lof_labelsManagementInterface > Lof\MarketplaceGraphQl\Model\Lof_labelsManagement


## Attributes



