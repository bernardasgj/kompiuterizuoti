<?php

namespace App\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class NotNull {
    public function __construct(
        public string $message = 'This field cannot be null'
    ) {}
}