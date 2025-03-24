# BeastBytes Token
BeastBytes Token creates, manages, and validates time limited tokens.

Tokens can be used for a variety of purposes, such as:
* Account verification
* Email confirmation
* Password reset

## Requirements
* PHP 8.1 or higher.

## Installation
This package is installed when installing one of the token storage packages:
* [BeastBytes Token PHP](httpshttps://github.com/beastbytes/token-php.git)
* [BeastBytes Token Db](https://github.com/beastbytes/token-db.git)
or the token factory package:
* [BeastBytes Token UUID4](https://github.com/beastbytes/token-uuid4.git)

To install the package directly:
```php
composer require beastbytes/token
```
or add the following to the 'require' section composer.json:
```json
"beastbytes/token": "^1.0"
```

## Implementation
Token makes no assumptions about the token format or the token storage.
Token generation is performed by a TokenFactoryInterface instance
([BeastBytes Token UUID4](https://github.com/beastbytes/token-uuid4.git) creates UUID V4 tokens), 
and storage is performed by a TokenStorageInterface instance
([BeastBytes Token PHP](httpshttps://github.com/beastbytes/token-php.git) and
[BeastBytes Token Db](https://github.com/beastbytes/token-db.git)
provide storage implementations for PHP files and databases respectively).

### TokenManager
TokenManager provides a simple interface for creating, retrieving, and deleting tokens; abstracting away the storage
implementation.

#### Configuration
To use with Yii's dependency injection container, 
see the configuration section of the token storage and factory packages.

### Token Types
Token types define the purpose of a token and its duration; token types are application specific
and must be defined by the application.
Token types are defined using an int backed enum that implements TokenTypeInterface; 
the enum name is the token type and the int value is the token duration in minutes. Each token type must have a unique
duration.

Example TokenType enum:
```php
enum TokenType: int implements TokenTypeInterface
{
    use TokenTypeTrait; // implements TokenTypeInterface
    
    case changePassword = 30;
    case confirmEmail = 15;
}
```

## Usage
Take the case of a user account verification process. Once the user has registered their account, the application will
raise an event that sends an email to the user; the email will contain a link that will verify that the user created the
account.

### Event handler
```php
$token = $tokenManager->add(TokenType::verifyAccount, $currentUser->getId());
$email->send($currentUser, $token);
```

### Verification action
On clicking the link in the email, the user is redirected to a page, and so action, that verifies the token;
the token is typically a URL parameter.
```php
public function actionVerifyAccount(
    #[RouteArgument('token')] string $tokenValue,
    TokenManager $tokenManager,
): ResponseInterface
{
    $token = $tokenManager->get($tokenValue);    
    
    if ($token instanceof Token) {    
        if ($token->isValid(TokenType::verifyAccount)) {
            $tokenManager->delete($token);
            
            $userId = $token->getUserId();
            // enable the user account
            
            // set success flash message
            return $this->redirect('/login');
        }
    
        // determine why the token is invalid
        $expired = $token->isExpired();
        if ($expired) {
            $tokenManager->delete($token);
            // set token expired flash message
        } else {    
            if (!$token->isType(TokenType::verifyAccount)) {
                // set wrong token type flash message
            }
        }
    } else {
        // set token does not exist flash message
    }
    
    return $this->redirect('/');      
}
```