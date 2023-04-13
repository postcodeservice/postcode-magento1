# TIG Postcode extension

**!!! This is a legacy extension for Adobe Magento 1. There is no support for this code, use at own risk !!!**


### Information
This is the official Postcode Service extension that integrates the Postcode Service directly into your Magento 1 environment. For more information about our Postcode Service please visit https://postcodeservice.com

To make use of the Postcode Service extension you are required to have a Client ID and Secure Code. The latest test CLient ID and Secure Code can be found on https://developers.postcodeservice.com. 

### Installation
We highly recommend installing extensions on a staging environment first.
The installation process is basic. 
1. Download this repository as a ZIP file. 
2. Unpack the ZIP file.
3. Connect with your Magento shop through SFTP.
4. Merge (don't overwrite!) the contents of the ZIP file into your Magento root folder.
5. Clear your Magento cache and relogin to your backend.

### Configuration
To get our extension to work you'll have to provide your Client ID and Secure Code. You can do this by opening up the Configuration page under the System tab.

Once you've opened the configuration page open up the "TIG Postcode Check" section at the left of your screen.
Magento will now show you a page where you can configure the extension. Here you can provide your Client ID, your Secure Code and the Domain you are using to call our service.

When you are done providing the required information, clear your Magento cache and try out your fresh installed Postcode check in your checkout.
