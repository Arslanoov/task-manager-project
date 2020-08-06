<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\AuthCode;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

final class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(AuthCode::class);
        $this->em = $em;
    }

    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCode();
    }

    /**
     * @param AuthCodeEntityInterface $accessTokenEntity
     * @throws UniqueTokenIdentifierConstraintViolationException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $accessTokenEntity): void
    {
        if ($this->exists($accessTokenEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($accessTokenEntity);
        $this->em->flush();
    }

    public function revokeAuthCode($tokenId): void
    {
        if ($token = $this->repo->find($tokenId)) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    /**
     * @param string $tokenId
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function isAuthCodeRevoked($tokenId): bool
    {
        return !$this->exists($tokenId);
    }

    /**
     * @param $id
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function exists($id): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.identifier)')
                ->andWhere('t.identifier = :identifier')
                ->setParameter(':identifier', $id)
                ->getQuery()->getSingleScalarResult() > 0;
    }
}
