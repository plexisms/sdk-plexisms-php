# PlexiSMS

[![Latest Version on Packagist](https://img.shields.io/packagist/v/plexisms/plexisms.svg?style=flat-square)](https://packagist.org/packages/plexisms/plexisms)
[![Total Downloads](https://img.shields.io/packagist/dt/plexisms/plexisms.svg?style=flat-square)](https://packagist.org/packages/plexisms/plexisms)

Official PHP library for the [PlexiSMS API](https://plexisms.com).

## Requirements

- PHP 7.4 or higher
- [Guzzle HTTP Client](https://github.com/guzzle/guzzle)

## Installation

Install the package via Composer:

```bash
composer require plexisms/plexisms
```

## Getting Started

### Get your API Key

You can get your API key from the [PlexiSMS dashboard](https://app.plexisms.com).

### Client Initialization

```php
require 'vendor/autoload.php';

use Plexisms\\Client;

$client = new Client('YOUR_API_KEY');
```

### Sending an SMS

```php
try {
    $response = $client->messages->create([
        'to' => '+243970000000', // Change
        'body' => 'Hello from PlexiSMS!',
        'senderId' => 'PlexiSMS' // Optional
    ]);

    print_r($response);
    // Array ( [id] => 123 [status] => sent ... )

} catch (\Plexisms\Exceptions\PlexismsException $e) {
    echo "Error: " . $e->getMessage();
}
```

> **Note:** The `senderId` is optional. If not provided, the default sender ID will be used. You can customize your sender ID from the [PlexiSMS dashboard](https://app.plexisms.com).

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Development

To contribute to this project, you will need to have [Composer](https_//getcomposer.org/) installed on your machine.

After cloning the repository, install the dependencies:

```bash
composer install
```

### Running tests

```bash
./vendor/bin/phpunit
```
