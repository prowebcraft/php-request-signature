# php-request-signature
Sign and check requests


### Usage examples

To sign your request create signature instance

#### Signing request

```php
// Creating a signer
$signer = new \Prowebcraft\Signature('SECRET_SALT');
// Create signature with path and/or request payload
$apiPath = '/api/login';
$payload = [
    'user' => "Elon Musk",
    'password' => 'mars2050'
];
$signature = $signer->sign($apiPath, $payload);
// Pass signature with Header or in payload
$payload['signature'] = $signature;
// Make request
```

#### Validating incoming request

```php
// Creating a signer checker
$signer = new \Prowebcraft\Signature('SECRET_SALT');

// Take request path
$path = $_SERVER['REQUEST_URI'];
// Collect request payload (can be simple POST or JSON Data)
$payload = $_POST ?: json_decode(file_get_contents('php://input'), true); 

// Check signature
$signature = $_SERVER['HTTP_SIGNATURE'] ?? $payload['signature'] ?? false;
if (!$signature) {
    throw new RuntimeException('Invaders must die');
}

// Validate integrity of request
if (!$signer->check($signature, $path, $payload)) {
    throw new RuntimeException('Invalid signature');
}

```
