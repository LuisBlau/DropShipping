## DropShipping Project

Laravel 8, Beautifort, eBay, Shopify REST API, Admin

- Get Products From Shopify REST API

  - ```
    /get-products-shopify
    ```

- Post Products To Shopify

  - ```
    /post-products-shopify
    ```

- Remove Products From Shopify

  - ```
    /remove-products-shopify
    ```

    

### 2021-09-23: 03:00:00

Updated the removed and uploaded items pages, and the cronjob functions for shopify.

*If you have migrated the "shopify_seller_products" table, you have to run following instruction.*

```
php artisan migrate:rollback --step=1
php artisan migrate
```

