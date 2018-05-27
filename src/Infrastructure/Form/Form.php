<?php
declare(strict_types=1);

namespace Infrastructure\Form;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

abstract class Form
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

    /**
     * @param array $data
     * @return bool
     */
    abstract public function check(array $data): bool;

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
     * @param array $fields
     * @return Form
     */
    public function rules(array $fields): self
    {
        $this->fields[$this->actualField]->rules($fields);
        return $this;
    }
    
    /**
     * @param array $data
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->validator) {
            $this->validator = Validator::create();
        }

        /** @var Field $field */
        foreach ($this->fields as $field) {
            foreach ($field->getRules() as $name => $params) {
                if (\is_string($params)) {
                    $this->validator->addRule($params);
                } else {
                    $this->validator->addRule($name, $params);
                }
            }

            try {
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
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
}