<?php

namespace Core;

interface Model {
    public function toArray(): array;
    public static function fromArray(array $data): self;
}
