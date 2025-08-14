<?php

namespace App\Model;

use Core\Attributes\Entity;
use Core\Model;

#[Entity('groups')]
class Group implements Model {
    public function __construct(
        private int $id, 
        private string $name
    ) {
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            (int)$data['id'],
            (string)$data['name']
        );
    }
}
