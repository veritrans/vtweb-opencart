Official Veritrans OpenCart (v1.5.x.x & lower) Extension
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

  * **Finish Redirect URL** in Settings to `http://[your shop’s homepage]/index.php?route=payment/veritrans/landing_redir&`

  * **Error Redirect URL** in Settings to `http://[your shop’s homepage]/index.php?route=payment/veritrans/landing_redir&`

  * **Unfinish Redirect URL** in Settings to `http://[your shop’s homepage]/index.php?route=payment/veritrans/landing_redir&`

#### Get help

* [Veritrans sandbox login](https://my.sandbox.veritrans.co.id/)
* [Veritrans sandbox registration](https://my.sandbox.veritrans.co.id/register)
* [Veritrans registration](https://my.veritrans.co.id/register)
* [Veritrans documentation](http://docs.veritrans.co.id)
* [Veritrans Opencart Documentation](http://docs.veritrans.co.id/vtweb/integration_opencart.html)
* Technical support [support@veritrans.co.id](mailto:support@veritrans.co.id)
