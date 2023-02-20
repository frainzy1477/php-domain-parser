<?php

declare(strict_types=1);

namespace Pdp;

use Iterator;
use Stringable;

final class Domain implements DomainName
{
    private function __construct(private RegisteredName $registeredName)
    {
        if ($this->registeredName->isIpv4()) {
            throw SyntaxError::dueToUnsupportedType($this->registeredName->toString());
        }
    }

    /**
     * @param array{registeredName: RegisteredName} $properties
     */
    public static function __set_state(array $properties): self
    {
        return new self($properties['registeredName']);
    }

    public static function fromIDNA2003(DomainNameProvider|Host|Stringable|string|int|null $domain): self
    {
        return new self(RegisteredName::fromIDNA2003($domain));
    }

    public static function fromIDNA2008(DomainNameProvider|Host|Stringable|string|int|null $domain): self
    {
        return new self(RegisteredName::fromIDNA2008($domain));
    }

    /**
     * @return Iterator<string>
     */
    public function getIterator(): Iterator
    {
        yield from $this->registeredName;
    }

    public function isAscii(): bool
    {
        return $this->registeredName->isAscii();
    }

    public function jsonSerialize(): ?string
    {
        return $this->registeredName->jsonSerialize();
    }

    public function count(): int
    {
        return count($this->registeredName);
    }

    public function value(): ?string
    {
        return $this->registeredName->value();
    }

    public function toString(): string
    {
        return $this->registeredName->toString();
    }

    public function label(int $key): ?string
    {
        return $this->registeredName->label($key);
    }

    /**
     * @return list<int>
     */
    public function keys(string $label = null): array
    {
        return $this->registeredName->keys($label);
    }

    /**
     * @return array<int, string>
     */
    public function labels(): array
    {
        return $this->registeredName->labels();
    }

    public function isIpv4(): bool
    {
        return $this->registeredName->isIpv4();
    }

    private function newInstance(RegisteredName $registeredName): self
    {
        if ($registeredName->value() === $this->registeredName->value()) {
            return $this;
        }

        return new self($registeredName);
    }

    public function toAscii(): self
    {
        return $this->newInstance($this->registeredName->toAscii());
    }

    public function toUnicode(): self
    {
        return $this->newInstance($this->registeredName->toUnicode());
    }

    /**
     * @throws CannotProcessHost
     */
    public function prepend(DomainNameProvider|Host|string|Stringable|null $label): self
    {
        return $this->newInstance($this->registeredName->prepend($label));
    }

    /**
     * @throws CannotProcessHost
     */
    public function append(DomainNameProvider|Host|string|Stringable|null $label): self
    {
        return $this->newInstance($this->registeredName->append($label));
    }

    public function withLabel(int $key, DomainNameProvider|Host|string|Stringable|null $label): self
    {
        return $this->newInstance($this->registeredName->withLabel($key, $label));
    }

    public function withoutLabel(int $key, int ...$keys): self
    {
        return $this->newInstance($this->registeredName->withoutLabel($key, ...$keys));
    }

    public function clear(): self
    {
        return $this->newInstance($this->registeredName->clear());
    }

    public function slice(int $offset, int $length = null): self
    {
        return $this->newInstance($this->registeredName->slice($offset, $length));
    }
}
