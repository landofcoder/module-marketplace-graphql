# Mage2 Module Lof MarketplaceGraphQl

    ``landofcoder/module-marketplace-graphql
``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
magento 2 marketplace graphql extension

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
 - Install the module composer by running `composer require landofcoder/module-marketplace-graphql`
 - enable the module by running `php bin/magento module:enable Lof_MarketplaceGraphQl`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### TODO
- Refactor Graphql queries
- Refactor Resolvers
- Add documendation for Graphql queries

## Queries

1. Get Seller Profile Info By Id

```
{
    lofSellerById(seller_id: Int!) {
        address
        banner_pic
        city
        commission_id
        company_description
        company_locality
        contact_number
        country
        customer_id
        email
        gplus_active
        gplus_id
        group
        image
        name
        page_layout
        region
        return_policy
        sale
        seller_id
        shipping_policy
        shop_title
        status
        store_id
        thumbnail
        seller_rates {
            items {
                created_at
                customer_id
                detail
                email
                nickname
                rate1
                rate2
                rate3
                rating_id
                seller_id
                status
                title
            }
            total_count
        }
  }
}






