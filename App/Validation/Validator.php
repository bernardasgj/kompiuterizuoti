<?php

namespace App\Validation;

use ReflectionClass;
use App\Validation\Attributes\NotBlank;
use App\Validation\Attributes\NotNull;

class Validator {
    public function validate(object $object): array {
        $errors = [];
        $reflection = new ReflectionClass($object);
        
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            
            foreach ($property->getAttributes() as $attribute) {
                $instance = $attribute->newInstance();
                
                if ($instance instanceof NotBlank && is_string($value) && trim($value) === '') {
                    $errors[$property->getName()] = $instance->message;
                }
                
                if ($instance instanceof NotNull && $value === null) {
                    $errors[$property->getName()] = $instance->message;
                }
            }
        }
        
        return $errors;
    }
}
