<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

class Collector implements \IteratorAggregate
{
    /**
     * @var array
     */
    protected $bag = [];

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return (function () {
            foreach ($this->bag as $key => $val) {
                yield $key => $val;
            }
        })();
    }
}