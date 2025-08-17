<?php

namespace App\Model;

use App\Repository\PersonRepository;
use App\Validation\Attributes\NotBlank;
use App\Validation\Attributes\NotNull;
use Core\Attributes\Entity;
use Core\Database;
use Core\Model;
use DateTime;

#[Entity('posts')]
class Post implements Model {
    private PersonRepository $personRepository;

    public function __construct(
        private int $id,
        #[NotNull(message: 'Author ID cannot be null')]
        private ?int $person_base_id,
        #[NotBlank(message: 'Content cannot be blank')]
        #[NotNull(message: 'Content cannot be null')]
        private ?string $content,
        #[NotNull(message: 'Creation date cannot be null')]
        private ?DateTime $created_at,
    ) {
        $this->personRepository = new PersonRepository(new Database());
    }

    public function getId(): int {
        return $this->id;
    }

    public function _getPerson(): Person {
        $person = $this->personRepository->findByWithDateRange(
            criteria: ['base_id' => $this->person_base_id],
            orderBy: ['valid_from' => 'DESC'],
            dateTo: $this->getCreatedAt()->format('Y-m-d H:m:i'),
        );
        // var_dump($this->getCreatedAt()->format('Y-m-d H:m:i'), $person);
        return $this->personRepository->findByWithDateRange(
            criteria: ['base_id' => $this->person_base_id],
            orderBy: ['valid_from' => 'DESC'],
            dateTo: $this->getCreatedAt()->format('Y-m-d H:m:i'),
        )[0];
    }

    public function getPersonBaseId(): ?int {
        return $this->person_base_id;
    }

    public function setPersonBaseId(?int $person_base_id): void {
        $this->person_base_id = $person_base_id;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(?string $content): void {
        $this->content = $content;
    }

    public function getCreatedAt(): ?DateTime {
        return $this->created_at;
    }

    public function setCreatedAt(?DateTime $created_at): void {
        $this->created_at = $created_at;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'person_base_id' => $this->person_base_id,
            'content' => $this->content,
            'created_at' => $this->created_at->format('Y-m-d\TH:i'),
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            (int)$data['id'],
            (int)$data['person_base_id'],
            (string)$data['content'],
            new DateTime($data['created_at']),
        );
    }
}