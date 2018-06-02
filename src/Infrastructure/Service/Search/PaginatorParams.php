<?php
declare(strict_types=1);

namespace Infrastructure\Service\Search;

use Slim\Http\Request;

class PaginatorParams
{
    /**
     * @var Request
     */
    private $request;

    private const OPERATORS = [
        'equals' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

    /**
     * PaginatorParams constructor.
     * @param Request $request
     * @param string $mapper
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $columns
     * @return array|null
     */
    public function parseCriteria(array $columns): ?array
    {
        $params = $this->request->getParams();

        $criteria = [];
        foreach ($columns as $column => $meta) {
            if (isset($params[$column])) {
                if (\is_string($params[$column])) {
                    $key = "{$column} = ?";
                    $criteria = [$key => $params[$column]];
                }

                if (\is_array($params[$column])) {
                    $operator = self::OPERATORS[array_keys($params[$column])[0]];

                    $key = "{$column} {$operator} ?";
                    $criteria = [$key => $params[$column]];
                }
            }
        }

        return $criteria;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return (int) $this->request->getParam('page', 1);
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return (int) $this->request->getParam('perPage', 10);
    }
}