<?php

namespace Ignite\Crud;

use Ignite\Support\VueProp;
use Illuminate\Support\Str;
use InvalidArgumentException;

class FieldDependency extends VueProp
{
    /**
     * List of available conditons. All conditions are available as [or{condition}].
     *
     * @var array
     */
    protected static $conditions = [
        'when', 'whenNot', 'whenContains', 'whenIn',
    ];

    /**
     * Dependency condition type.
     *
     * @var string
     */
    protected $condition;

    /**
     * Attribute name.
     *
     * @var string
     */
    protected $attribute;

    /**
     * Dependency value.
     *
     * @var string
     */
    protected $value;

    /**
     * Create new Dependency.
     *
     * @param  string     $contains
     * @param  string     $attribute
     * @param  string|int $value
     * @return void
     */
    public function __construct(string $condition, $attribute, $value)
    {
        if (! self::conditionExists($condition)) {
            throw new InvalidArgumentException(
                "Condition [{$condition}] is not available"
            );
        }

        $this->condition = $condition;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    /**
     * Determines if a condition exists.
     *
     * @param  string $condition
     * @return bool
     */
    public static function conditionExists(string $condition)
    {
        if (! Str::startsWith($condition, 'or')) {
            return in_array($condition, self::$conditions);
        }

        foreach (self::$conditions as $available) {
            $orCondition = 'or'.ucfirst($available);
            if ($orCondition == $condition) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get attribute name.
     *
     * @return string
     */
    public function getAttributeName()
    {
        return $this->attribute;
    }

    /**
     * Gets condition.
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Get dependency value.
     *
     * @return string|int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Create new Dependency instance.
     *
     * @param  string     $condition
     * @param  string     $attribute
     * @param  string|int $value
     * @return self
     */
    public static function make(string $condition, $attribute, $value)
    {
        return new self($condition, $attribute, $value);
    }

    /**
     * Render dependency for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'condition' => $this->condition,
            'attribute' => $this->attribute,
            'value'     => $this->value,
        ];
    }
}
