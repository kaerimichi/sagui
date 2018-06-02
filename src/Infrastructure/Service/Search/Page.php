<?php
declare(strict_types=1);

namespace Infrastructure\Service\Search;

class Page implements \ArrayAccess
{
    /**
     * @var array
     */
    private $page;

    /**
     * Page constructor.
     * @param array $data
     * @param int $total
     * @param int $page
     * @param int $pageSize
     */
    public function __construct(array $data, int $total, int $page, int $pageSize)
    {
        $this->page = [
            'data' => [],
            'total' => 0,
            'page_total' => 0,
            'first' => 0,
            'prev' => 0,
            'page' => 0,
            'next' => 0,
            'last' => 0
        ];

        if (\count($data) > 0) {
            $lastPage = (int) floor($total / $pageSize);

            $this->page['data'] = $data;
            $this->page['total'] = $total;
            $this->page['page_total'] = \count($data);
            $this->page['first'] = 1;
            $this->page['prev'] = ($page - 1) === 0 ? 1 : $page - 1;
            $this->page['page'] = $page;
            $this->page['next'] = $page < $lastPage ? $page + 1 : $lastPage;
            $this->page['last'] = $lastPage;
        }
    }

    /**
     * @return array|null
     */
    public function toArray(): ?array
    {
        return $this->page;
    }

    public function offsetExists($offset)
    {
        return isset($this->page[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->page[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException('This object is read only.');
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('This object is read only.');
    }
}