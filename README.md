vtweb-opencart
==============

##Opencart Veritrans integration library

1 - Extract vtweb-opencart-master.zip

2 - Locate the root opencart directory of your shop via FTP connection

3 - Copy the 'admin', 'catalog', 'image' and 'system' folders into opencart root folder.

4 - Import token.sql to your shop's database

6 - In your opencart admin area, enable the Veritrans plug-in and insert your merchant details (Merchant ID and Merchant Hash Key)

7 - Login into your Veritrans account and change the Payment Notification URL in Settings to http://[your shop's homepage]/index.php?route=payment/veritrans/payment_notification

