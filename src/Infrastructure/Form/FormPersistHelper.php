<?php
declare(strict_types=1);

namespace Infrastructure\Form;

use Atlas\Orm\Atlas;
use Atlas\Orm\Mapper\RecordInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Sqlite\Select;
use Infrastructure\Exception\HandlerException;
use Infrastructure\Exception\NotFoundException;
use Infrastructure\Exception\ValidationException;

class FormPersistHelper
{
    /**
     * @var Atlas
     */
    private $atlas;

    /**
     * @var string
     */
    private $mapper;

    /**
     * @var Form
     */
    private $form;

    public function __construct(Atlas $atlas)
    {
        $this->atlas = $atlas;
    }

    /**
     * @param string $mapper
     * @return FormPersistHelper
     */
    public function mapper(string $mapper): self
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @param Form $form
     * @return FormPersistHelper
     */
    public function form(Form $form): self
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @throws ValidationException
     */
    private function validate(): void
    {
        if (!$this->form->validate()) {
            throw new ValidationException($this->form->getErrors());
        }
    }

    /**
     * @return RecordInterface
     * @throws HandlerException
     * @throws ValidationException
     */
    public function register(): RecordInterface
    {
        $this->validate();

        $data = array_merge($this->form->getData(), ['created_at' => (new \DateTime())->format('Y-m-d H:i:s')]);
        $record = $this->atlas->newRecord($this->mapper, $data);

        if (!$this->atlas->insert($record)) {
            throw new HandlerException($this->atlas->getException()->getMessage());
        }

        return $record;
    }

    /**
     * @param int $id
     * @return RecordInterface
     * @throws HandlerException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function updateById(int $id): RecordInterface
    {
        return $this->update($this->atlas->fetchRecord($this->mapper, $id));
    }

    /**
     * @param array $criteria
     * @return RecordInterface
     * @throws HandlerException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function updateByCriteria(array $criteria): RecordInterface
    {
        /** @var SelectInterface $select */
        $select = $this->atlas->select($this->mapper);
        foreach ($criteria as $criterion) {
            $select = $select->where($criterion);
        }

        return $this->update($select->fetchRecord());
    }

    /**
     * @param RecordInterface|null $record
     * @return RecordInterface
     * @throws HandlerException
     * @throws NotFoundException
     * @throws ValidationException
     */
    protected function update(?RecordInterface $record): RecordInterface
    {
        if (!$record) {
            throw new NotFoundException(
                'entity_not_found',
                'The supplied entity wasnt found for update operation.',
                200
            );
        }

        $this->validate();

        $merged = array_merge(
            $record->getArrayCopy(),
            $this->form->getData(),
            ['updated_at' => (new \DateTime())->format('Y-m-d H:i:s')]
        );
        $record->set($merged);

        if (!$this->atlas->update($record)) {
            throw new HandlerException($this->atlas->getException()->getMessage());
        }

        return $record;
    }
}