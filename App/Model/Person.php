<?php

namespace App\Model;

use App\Repository\GroupRepository;
use Core\Attributes\Entity;
use Core\Model;
use DateTime;

#[Entity('person')]
class Person implements Model {
    private GroupRepository $groupRepository;

    public function __construct(
        private int $id,
        private int $baseId,
        private string $name,
        private string $surname,
        private ?int $group_id,
        private DateTime $validFrom
    ) {
        $this->groupRepository = new GroupRepository();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getBaseId(): int {
        return $this->baseId;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSurname(): string {
        return $this->surname;
    }

    public function getFullName(): string {
        return $this->getName() . ' ' . $this->getSurname();
    }

    public function _getGroup(): Group {
        return $this->groupRepository->findOneBy(['id' => $this->group_id]);
    }

    public function getGroup(): ?int {
        return $this->group_id;
    }

    public function setGroup(?int $group_id): void {
        $this->group_id = $group_id;
    }

    public function getValidFrom(): DateTime {
        return $this->validFrom;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'base_id' => $this->baseId,
            'name' => $this->name,
            'surname' => $this->surname,
            'group' => $this->group_id,
            'valid_from' => $this->validFrom->format('Y-m-d')
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            (int)$data['id'],
            (int)$data['base_id'],
            (string)$data['name'],
            (string)$data['surname'],
            (int)$data['group_id'],
            new DateTime($data['valid_from'])
        );
    }
}
