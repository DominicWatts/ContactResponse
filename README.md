# Magento 2 Contact Response

![phpcs](https://github.com/DominicWatts/ContactResponse/workflows/phpcs/badge.svg)

![PHPCompatibility](https://github.com/DominicWatts/ContactResponse/workflows/PHPCompatibility/badge.svg)

![PHPStan](https://github.com/DominicWatts/ContactResponse/workflows/PHPStan/badge.svg)

Response email yes / no field in admin with email template config. This toggles an automated response to contact us submission.

# Install instructions #

`composer require dominicwatts/contactresponse`

`php bin/magento setup:upgrade`

`php bin/magento setup:di:compile`

# Usage instructions #

Configure optional email values in admin

![Screenshot](https://i.snipboard.io/Mjf01x.jpg)

![Screenshot](https://i.snipboard.io/R0WKGQ.jpg)

Use contact form

Comnfirmation email will be sent back to contact
