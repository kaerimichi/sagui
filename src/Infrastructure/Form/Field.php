<?php
declare(strict_types=1);

namespace Infrastructure\Form;

class Field
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private $rules;

    /**
     * Field constructor.
     * @param string $field
     * @param $value
     */
    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array $rules
     * @return Form
     */
    public function rules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}