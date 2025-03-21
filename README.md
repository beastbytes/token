Token types are application specific and must therefore be defined by the application. Token types are defined in 
an int backed enum that implements the TokenType interface; the enum name is the token type, 
and the int value is the token duration in minutes.

Example TokenType enum:
```php
enum TokenType: int implements TokenTypeInterface
{
    use TokenTypeTrait; // implements TokenTypeInterface
    
    case changePassword = 30;
    case confirmEmail = 15;
}
```