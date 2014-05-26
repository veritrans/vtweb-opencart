Veritrans official Opencart plug-in
===================================

Veritrans :heart: Opencart!

This is the official Veritrans plug-in for the Opencart e-commerce platform.

### Installation

1. Extract the `vtweb-opencart-master.zip` file.

2. Locate the root _Opencart_ directory of your shop via FTP connection.

3. Copy the `admin`, `catalog`, `image` and `system` folders into your _Opencart's_ root folder.

4. Import the `token.sql` file into your _Opencart_ shop database.

6. In your _Opencart_ admin area, enable the Veritrans plug-in and insert your merchant details (Merchant ID and Merchant Hash Key)

7. Login into your Veritrans account and change the following options: 
   
  * **Payment Notification URL** in Settings to `http://[your shop's homepage]/index.php?route=payment/veritrans/payment_notification`

  * **Finish Redirect URL** in Settings to `http://[your shop's homepage]/index.php?route=checkout/success&`

  * **Error Redirect URL** in Settings to `http://[your shop's homepage]/index.php?route=checkout/failure&`

  * **Unfinish Redirect URL** in Settings to `http://[your shop's homepage]/index.php`

