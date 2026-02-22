<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Faktura as FakturaRR;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class OpenAndSendRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    /**
     * @param array<int, Faktura | FakturaRR> $faktury
     */
    public function __construct(
        public readonly FormCode $formCode,
        public readonly array $faktury,
        public readonly Optional | bool $offlineMode = new Optional(),
    ) {
    }
}
