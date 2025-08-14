<?php

namespace App\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class NotBlank {
    public function __construct(
        public string $message = 'This field cannot be blank'
    ) {}
}