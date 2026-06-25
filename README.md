# Veo 3 PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/veo-3.1)](https://packagist.org/packages/runapi-ai/veo-3.1)
[![License](https://img.shields.io/github/license/runapi-ai/veo-3.1-php)](https://github.com/runapi-ai/veo-3.1-php/blob/main/LICENSE)

The Veo 3 PHP SDK is the Composer package for Veo 3 on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/veo-3.1
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Veo31\Veo31Client;

$client = new Veo31Client(); // reads RUNAPI_API_KEY

$task = $client->textToVideo->create([
    'model' => 'veo-3.1',
    'prompt' => 'A precise product render on white marble',
]);

$status = $client->textToVideo->get($task->id);

$result = $client->textToVideo->run([
    'model' => 'veo-3.1',
    'prompt' => 'A serene mountain lake at dawn',
]);

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/veo-3.1
- SDK docs: https://runapi.ai/docs#sdk-veo-3.1
- Product docs: https://runapi.ai/docs#veo-3.1
- Pricing and rate limits: https://runapi.ai/models/veo-3.1/veo-3.1
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/veo-3.1-php
- Multi-language SDK repository: https://github.com/runapi-ai/veo-3.1-sdk

## License

Licensed under the Apache License, Version 2.0.
