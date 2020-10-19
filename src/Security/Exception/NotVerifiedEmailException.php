<?php
namespace App\Security\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class NotVerifiedEmailException extends CustomUserMessageAuthenticationException
{
    public function __construct(
        string $message = 'Ce compte ne comporte pas de mail vérifié',
        array $messageData = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $messageData, $code, $previous);
    }
}