# Adobe Sign PHP SDK

https://acrobat.adobe.com/us/en/sign.html

## Installation

To install, use composer:

```
composer require ajohnson6494/adobe-sign-php
```

## Documentation

https://secure.na1.echosign.com/public/docs/restapi/v5

### Example Usage

```php
session_start();

$provider = new ajohnson6494\AdobeSignProvider([
    'clientId'          => 'your_client_id',
    'clientSecret'      => 'your_client_secret',
    'redirectUri'       => 'your_callback',
    'scope'             => [
          'scope1:type',
          'scope2:type'
    ]
]);

$adobeSign = new AdobeSign($provider);

if (!isset($_GET['code'])) {
    $authorizationUrl = $adobeSign->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authorizationUrl);
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
} else {
    $accessToken = $adobeSign->getAccessToken($_GET['code']);
    $adobeSign->setAccessToken($accessToken->getToken());
    $adobeSign->createAgreement([
        'documentCreationInfo' => [
            'fileInfos'         => [
                'libraryDocumentId' => 'your_library_document_id'
            ],
            'name'              => 'My Document',
            'signatureType'     => 'ESIGN',
            'recipientSetInfos' => [
                'recipientSetMemberInfos' => [
                    'email' => 'test@gmail.com'
                ],
                'recipientSetRole'        => [
                    'SIGNER'
                ]
            ],
            'mergeFieldInfo'    => [
                [
                    'fieldName'    => 'Name',
                    'defaultValue' => 'John Doe'
                ]
            ],
            'signatureFlow'     => 'SENDER_SIGNATURE_NOT_REQUIRED'
        ]
    ]);
}
```

### Generate Multipart Stream for transient document upload
```php
$file_path = '/path/to/local/document';

$file_stream = Psr7\FnStream::decorate(Psr7\stream_for(file_get_contents($file_path)), [
    'getMetadata' => function() use ($file_path) {
        return $file_path;
    }
]);

$multipart_stream   = new Psr7\MultipartStream([
    [
        'name'     => 'File',
        'contents' => $file_stream
    ]
]);

$transient_document = $adobeSign->uploadTransientDocument($multipart_stream);
```
