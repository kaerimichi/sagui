<?php
declare(strict_types=1);

namespace Infrastructure\Form;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class Form implements \ArrayAccess
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var string
     */
    private $actualField;

    public function offsetExists($offset): bool
    {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset): ?Field
    {
        return $this->fields[$offset] ?? null;
    }

    public function offsetSet($offset, $value): self
    {
        return $this->field($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->fields[$offset]);
    }

    /**
     * @param string $field
     * @param $value
     * @return Form
     */
    public function field(string $field, $value): self
    {
        $instance = new Field($field, $value);
        $this->fields[$field] = $instance;
        $this->actualField = $field;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $out = [];
        /** @var Field $field */
        foreach ($this->fields as $field) {
            $out[$field->getField()] = $field->getValue();
        }

        return $out;
    }

    /**
     * @param array $fields
     * @return Form
     */
    public function rules(array $fields): self
    {
        $this->fields[$this->actualField]->rules($fields);
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->validator) {
            $this->validator = Validator::create();
        }

        /** @var Field $field */
        foreach ($this->fields as $field) {
            try {
                $this->addValidatorRules($field);
                $this->validator->assert($field->getValue());
            } catch (NestedValidationException $exception) {
                $this->errors[$field->getField()] = $exception->getMessages();
            } finally {
                $this->validator->removeRules();
            }
        }

        return $this->errors === null;
    }

    /**
     * @param Field $field
     */
    protected function addValidatorRules(Field $field): void
    {
        foreach ($field->getRules() as $name => $params) {
            if (\is_string($params)) {
                $this->validator->addRule($params);
            } else {
                $this->validator->addRule($name, $params);
            }
        }
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
}