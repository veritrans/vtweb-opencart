Official Veritrans OpenCart Extension
===================================

Veritrans :heart: OpenCart!

This is the official Veritrans extension for the OpenCart E-commerce platform.

## Installation

1. Extract the `vtweb-opencart-master.zip` file.

2. Locate the root _OpenCart_ directory of your shop via FTP connection.

3. Copy the `admin`, `catalog`, and `system` folders into your _OpenCart's_ root folder.

5. In your _OpenCart_ admin area, enable the Veritrans plug-in and insert your merchant details (server key and client key).

6. Login into your Veritrans account and change the following options:

  * **Payment Notification URL** in Settings to `http://[your shop's homepage]/index.php?route=payment/veritrans/payment_notification`

  * **Finish Redirect URL** in Settings to `http://[your shop's homepage]/index.php?route=checkout/success&`

  * **Error Redirect URL** in Settings to `http://[your shop's homepage]/index.php`

  * **Unfinish Redirect URL** in Settings to `http://[your shop's homepage]/index.php`

