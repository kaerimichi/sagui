<?php
declare(strict_types=1);

namespace Infrastructure\Form;

use Atlas\Orm\Atlas;
use Atlas\Orm\Mapper\RecordInterface;
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
     * @param array $data
     * @return RecordInterface
     * @throws HandlerException
     * @throws ValidationException
     */
    public function register(array $data): RecordInterface
    {
        if (!$this->form->check($data)) {
            throw new ValidationException($this->form->getErrors());
        }

        array_merge($data, ['created_at' => (new \DateTime())->format('Y-m-d H:i:s')]);
        $record = $this->atlas->newRecord($this->mapper, $data);

        if (!$this->atlas->insert($record)) {
            throw new HandlerException($this->atlas->getException()->getMessage());
        }

        return $record;
    }

    /**
     * @param array $data
     * @param int $id
     * @return RecordInterface
     * @throws HandlerException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function update(array $data, int $id): RecordInterface
    {
        if (!$this->form->check($data)) {
            throw new ValidationException($this->form->getErrors());
        }

        $record = $this->atlas->fetchRecord($this->mapper, $id);
        if (!$record) {
            throw new NotFoundException(
                'entity_not_found',
                'The supplied entity wasnt found for update operation.',
                200
            );
        }

        $record->set($data);

        if (!$this->atlas->update($record)) {
            throw new HandlerException($this->atlas->getException()->getMessage());
        }

        return $record;
    }
}