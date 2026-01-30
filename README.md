# TerraReach PHP SDK

[![Latest Stable Version](https://img.shields.io/packagist/v/origyns/terrareach-sms-php.svg)](https://packagist.org/packages/origyns/terrareach-sms-php)
[![License](https://img.shields.io/packagist/l/origyns/terrareach-sms-php.svg)](https://packagist.org/packages/origyns/terrareach-sms-php)

**SMS for Developers and Marketers.** The official PHP SDK for the [TerraReach](https://terrareach.com) SMS Gateway, built for speed, simplicity, and reliability in the Sri Lankan market.

---

## Features

* **Single SMS:** Send high-priority messages to individuals.
* **Bulk SMS:** Send to thousands of recipients in a single API call.
* **Account Stats:** Real-time balance and usage monitoring.
* **Minimalist Design:** Clean PSR-4 implementation with a short, readable namespace.

---

## Installation

You can install the package via Composer:

```bash
composer require origyns/terrareach-sms-php
```

---

## Quick Start

### 1. Initialize the Client

You need your API Key and your approved Sender ID (Mask).

```php
require 'vendor/autoload.php';

use TerraReach\Client;

$apiKey = 'your_api_key_here';
$mask = 'SenderID';

$terra = new Client($apiKey, $mask);
```

### 2. Send a Single SMS

```php
```php
$response = $terra->sendSms('94771234567', 'Hello from TerraReach!');

// You can also override the default mask for a specific message
$response = $terra->sendSms('94771234567', 'OTP: 1234', 'OTPMASK');

if ($response['status'] === 'success') {
    echo "Message sent!";
}
```

### 3. Send Bulk SMS

Pass an array of phone numbers. The SDK automatically handles the plural payload requirements.

```php
$numbers = ['94771234567', '94777654321', '94711223344'];
$terra->sendBulkSms($numbers, 'This is a broadcast message.');
```

### 4. Check Balance & Stats

```php
$stats = $terra->getStats();
echo "Your current balance is: " . $stats['balance'];
```

---

## API Reference

| Method | Parameters | Description |
|--------|-----------|-------------|
| `sendSms()` | `$phoneNumber`, `$message`, `$mask` | Sends a single SMS to a string phone number. |
| `sendBulkSms()` | `array $phoneNumbers`, `$message`, `$mask` | Sends SMS to an array of phone numbers. |
| `getStats()` | none | Returns account usage and balance. |

---

## Security

If you discover any security-related issues, please email support@terrareach.com instead of using the issue tracker.

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

Made with ❤️ by ORIGYN