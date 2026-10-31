<?php

namespace App\Services\Payment;

final readonly class PaymentResult
{
    private function __construct(
        public string $status,          // paid | redirect | failed
        public ?string $reference = null,
        public ?string $redirectUrl = null,
        public ?string $error = null,
    ) {}

    public static function paid(string $reference): self
    {
        return new self('paid', reference: $reference);
    }

    public static function redirect(string $url, ?string $reference = null): self
    {
        return new self('redirect', reference: $reference, redirectUrl: $url);
    }

    public static function failed(string $error): self
    {
        return new self('failed', error: $error);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function needsRedirect(): bool
    {
        return $this->status === 'redirect';
    }
}
