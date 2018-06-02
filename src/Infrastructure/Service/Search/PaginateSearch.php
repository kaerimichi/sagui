<?php
declare(strict_types=1);

namespace Infrastructure\Service\Search;

class PaginateSearch extends SimpleSearch
{
    /**
     * @param string $mapper
     * @param PaginatorParams $params
     * @return Page
     */
    public function findByPage(string $mapper, PaginatorParams $params): Page
    {
        $criteria = $params->parseCriteria($this->atlas->mapper($mapper)->getTable()->getCols());

        $select = $this->atlas->select($mapper);
        foreach ($criteria as $field => $value) {
            $select = $select->where($field, $value);
        }

        $offset = ($params->getPage()-1) * $params->getPageSize();
        $records = $select->limit($params->getPageSize())->offset($offset)->fetchRecordSet()->getArrayCopy();

        if (\count($records) === 0) {
            return new Page([], 0, 0, 0);
        }

        return new Page($records, $select->fetchCount(), $params->getPage(), $params->getPageSize());
    }
}