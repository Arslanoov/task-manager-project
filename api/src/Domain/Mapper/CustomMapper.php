<?php

declare(strict_types=1);

namespace Domain\Mapper;

use Cycle\ORM\Mapper\DatabaseMapper;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Schema;
use stdClass;

class CustomMapper extends DatabaseMapper
{
    private string $class;

    public function __construct(ORMInterface $orm, string $role)
    {
        parent::__construct($orm, $role);
        $this->class = $orm->getSchema()->define($role, Schema::ENTITY);
    }

    public function init(array $data): array
    {
        return [new stdClass(), $data];
    }

    public function hydrate($entity, array $data)
    {
        return new stdClass();
    }

    public function extract($entity): array
    {
        return [];
    }

    protected function fetchFields($entity): array
    {
        return array_intersect_key(
            $this->extract($entity),
            array_flip($this->columns)
        );
    }
}