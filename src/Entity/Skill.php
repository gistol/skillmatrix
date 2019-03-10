<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Skill\Doctrine")
 */
class Skill implements \JsonSerializable
{
    private const MAX_NAME_LENGTH = 255;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function rename(string $newName): void
    {
        $this->setName($newName);
    }

    private function setName(string $name): void
    {
        if (strlen($name) === 0) {
            throw new \InvalidArgumentException('Name can not be empty.');
        }

        if (strlen($name) > self::MAX_NAME_LENGTH) {
            $message = sprintf('Name is too long, max. %d chars is allowed.', self::MAX_NAME_LENGTH);

            throw new \InvalidArgumentException($message);
        }

        $this->name = $name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
