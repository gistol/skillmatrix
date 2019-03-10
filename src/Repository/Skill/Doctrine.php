<?php declare(strict_types=1);

namespace App\Repository\Skill;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method \App\Entity\Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Entity\Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Entity\Skill[]    findAll()
 * @method \App\Entity\Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Doctrine extends ServiceEntityRepository implements SkillRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    public function getById(int $skillId): Skill
    {
        $skill = $this->find($skillId);

        if ($skill === null) {
            $message = sprintf('Skill (id=%d) not found.', $skillId);

            throw new SkillNotFound($message);
        }

        return $skill;
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function store(Skill $skill): void
    {
        try {
            $this->_em->persist($skill);
            $this->_em->flush();
        } catch (\Throwable $e) {
            $message = sprintf('Skill (%s) was not be saved.', $skill->getName());

            throw new SkillNotSaved($message);
        }
    }

    public function delete(Skill $skill): void
    {
        try {
            $this->_em->remove($skill);
            $this->_em->flush();
        } catch (ORMException $e) {
            $message = sprintf('Skill (%s) was not deleted.', $skill->getName());

            throw new SkillNotDeleted($message);
        } catch (ForeignKeyConstraintViolationException $e) {
            // In the DB are Ratings with this Skill
            $message = sprintf('Skill (%s) was not deleted because there are Ratings with this Skill.', $skill->getName());

            throw new SkillNotDeleted($message);
        }
    }
}
