<?php
declare(strict_types=1);

namespace Infrastructure\Service\Search;

use Atlas\Orm\Atlas;

class SimpleSearch
{
    /**
     * @var Atlas
     */
    protected $atlas;

    /**
     * SimpleFinder constructor.
     * @param Atlas $atlas
     */
    public function __construct(Atlas $atlas)
    {
        $this->atlas = $atlas;
    }

    /**
     * @param string $mapper
     * @param int $id
     * @return array|null
     */
    public function findById(string $mapper, int $id): ?array
    {
        $data = $this->atlas->fetchRecord($mapper, $id);
        return $data ? $data->getArrayCopy(): null;
    }

    /**
     * @param string $mapper
     * @param array $criteria
     * @return array|null
     */
    public function findOneBy(string $mapper, array $criteria): ?array
    {
        $select = $this->atlas->select($mapper);
        foreach ($criteria as $field => $value) {
            $select = $select->where($field, $value);
        }

        $record = $select->fetchRecord();
        return $record ? $record->getArrayCopy() : null;
    }

    /**
     * @param string $mapper
     * @param array $criteria
     * @return array
     */
    public function findBy(string $mapper, array $criteria): array
    {
        $select = $this->atlas->select($mapper);
        foreach ($criteria as $field => $value) {
            $select = $select->where($field, $value);
        }

        return $select->fetchRecordSet()->getArrayCopy();
    }
}