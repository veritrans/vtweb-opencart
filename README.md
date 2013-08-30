vtweb-opencart
==============

##Opencart Veritrans integration library
- Extract the vtweb-opencart-master.zip
- Locate the main directory of your shop via FTP connection
- Open vtweb-opencart-master folder. Inside this folder you will find three folders: admin, catalog, & system. These are the folders that you will need to upload to install the extension
- To install the extension, simply drag these three folders to your opencart root directory.
- Import token.sql to your shop's database
- Now you can install veritrans' payment module from your admin page
- Insert Merchant ID, Merchant Hash Key, and set status Enabled on your veritrans form

##Integrate Veritrans' Payment Notification
- Login to https://payments.veritrans.co.id/map/
- Go to Settings -> VT-Web Configuration
- Insert [your shop's homepage]/index.php?route=payment/veritrans/payment_notification into Payment Notification URL's field
- Click Update
