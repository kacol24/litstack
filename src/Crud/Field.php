<?php

namespace Fjord\Crud;

use Closure;
use Fjord\Exceptions\Traceable\BadMethodCallException;
use Fjord\Exceptions\Traceable\InvalidArgumentException;
use Fjord\Exceptions\Traceable\MissingAttributeException;
use Fjord\Support\HasAttributes;
use Fjord\Support\VueProp;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;

class Field extends VueProp
{
    use ForwardsCalls, HasAttributes;

    /**
     * Model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Form instance.
     *
     * @var string
     */
    protected $formInstance;

    /**
     * Authorize closure for field.
     *
     * @var Closure
     */
    protected $authorize;

    /**
     * Properties passed to Vue component.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Saveable field.
     *
     * @var bool
     */
    protected $save = true;

    /**
     * Fill to attribute.
     *
     * @var bool
     */
    public $fill = true;

    /**
     * Repository class.
     *
     * @var string
     */
    protected $repository;

    /**
     * Create new Field instance.
     *
     * @param string      $id
     * @param string      $model
     * @param string|null $routePrefix
     * @param mixed       $form
     */
    public function __construct(string $id, string $model, $routePrefix, $form)
    {
        $this->model = $model;
        $this->formInstance = $form;

        $this->validateFieldId($model, $id);

        $this->setAttribute('id', $id);
        $this->setAttribute('local_key', $id);
        $this->setAttribute('route_prefix', $routePrefix);
        $this->setAttribute('component', $this->component);
        $this->setAttribute('readonly', false);
        $this->setAttribute('class', '');

        $this->setDefaultsFromClassMethods();
        $this->setDefaultAttributes();
        $this->mergeRequiredAttributes();
    }

    /**
     * Get repository instance.
     *
     * @return null|instance
     */
    public function getRepository()
    {
        if (! $this->repository) {
            return;
        }

        return app()->make($this->repository, [
            'field' => $this,
        ]);
    }

    /**
     * Validate field id.
     *
     * @param  string $model
     * @param  string $id
     * @return void
     */
    protected function validateFieldId($model, $id)
    {
        if ($id == 'media') {
            throw new InvalidArgumentException('The field id cannot be "media".', [
                'function' => '__call',
            ]);
        }
    }

    /**
     * Get model class.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get field title.
     *
     * @return string
     */
    public function getTitle()
    {
        return collect(explode('_', $this->getAttribute('id')))->map(function ($word) {
            return ucfirst($word);
        })->implode(' ');
    }

    /**
     * Fill model.
     *
     * @param mixed  $model
     * @param string $attributeName
     * @param mixed  $attributeValue
     *
     * @return void
     */
    public function fillModel($model, $attributeName, $attributeValue)
    {
    }

    /**
     * Set readonly attribute.
     *
     * @param bool $readonly
     *
     * @return $this
     */
    public function readonly(bool $readonly = true)
    {
        $this->setAttribute('readonly', $readonly);

        return $this;
    }

    /**
     * Add dependency.
     *
     * @param  FieldDependency $dependency
     * @return $this
     */
    public function addDependency(FieldDependency $dependency)
    {
        if ($this->hasAttribute('dependencies')) {
            $this->dependencies[] = $dependency;
        } else {
            $this->setAttribute('dependencies', collect([$dependency]));
        }

        return $this;
    }

    /**
     * Resolve field dependencies.
     *
     * @param  self|string $field
     * @param  int|string  $value
     * @return array
     *
     * @throws InvalidArgumentException
     */
    protected function resolveDependencyArguments($field, $value)
    {
        if (is_string($field)) {
            if (! $field = $this->formInstance->findField($fieldId = $field)) {
                throw new InvalidArgumentException("Couldn't find field [{$fieldId}]");
            }
        }

        return [$field, $value];
    }

    /**
     * Get dependencies.
     *
     * @return Collection
     */
    public function getDependencies()
    {
        return $this->dependencies ?: collect([]);
    }

    /**
     * Is field saveable.
     *
     * @return bool
     */
    public function canSave()
    {
        return $this->save;
    }

    /**
     * Should field be registered in form.
     *
     * @return bool
     */
    public function shouldBeRegistered()
    {
        return true;
    }

    /**
     * Format value before saving it to database.
     *
     * @param string $value
     *
     * @return void
     */
    public function format($value)
    {
        return $value;
    }

    /**
     * Cast model value for e.g. boolean.
     *
     * @param Model $value
     *
     * @return mixed
     */
    public function cast($value)
    {
        return $value;
    }

    /**
     * Transform model value.
     *
     * @param Model $value
     *
     * @return mixed
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * Merge required properties to allow defining required attributes in traits.
     *
     * @return void
     */
    public function mergeRequiredAttributes()
    {
        foreach (get_object_vars($this) as $propertyName => $propertyValue) {
            if ($propertyName == 'required') {
                continue;
            }
            if (! Str::endsWith($propertyName, 'Required')) {
                continue;
            }
            $this->required = array_merge($this->required, $propertyValue);
        }
    }

    /**
     * Check if all required props have been set.
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function checkComplete()
    {
        $missing = [];
        foreach ($this->required as $prop) {
            if (array_key_exists($prop, $this->attributes)) {
                continue;
            }

            $missing[] = $prop;
        }

        if (empty($missing)) {
            return true;
        }

        throw new MissingAttributeException(sprintf(
            'Missing required attributes: [%s] for %s field "%s"',
            implode(', ', $missing),
            lcfirst(last(explode('\\', static::class))),
            $this->attributes['id']
        ));
    }

    /**
     * Set slot component.
     *
     * @param string $slot
     * @param string $component
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function slot(string $slot, string $component)
    {
        if (! $this->slotExists($slot)) {
            $field = class_basename(static::class);

            throw new InvalidArgumentException("Slot {$slot} does not exist for Field {$field}");
        }

        $this->{$this->getSlotMethodName($slot)}($component);

        $this->attributes['slots'][$slot] = $component;

        return $this;
    }

    /**
     * Check if slot exists.
     *
     * @param string $slot
     *
     * @return void
     */
    public function slotExists(string $slot)
    {
        return in_array(
            $this->getSlotMethodName($slot),
            get_class_methods($this)
        );
    }

    /**
     * Get slot method name.
     *
     * @param string $slot
     *
     * @return string
     */
    protected function getSlotMethodName(string $slot)
    {
        return Str::camel("{$slot}_slot");
    }

    /**
     * Set authorize closure.
     *
     * @param Closure $closure
     *
     * @return void
     */
    public function authorize(Closure $closure)
    {
        $this->authorize = $closure;
    }

    /**
     * Execute authorize method.
     *
     * @return bool
     */
    public function authorized()
    {
        if (! $this->authorize) {
            return true;
        }

        $closure = $this->authorize;

        return $closure(fjord_user());
    }

    /**
     * Set default attributes from class method.
     *
     * @return void
     */
    public function setDefaultsFromClassMethods()
    {
        foreach (get_class_methods($this) as $method) {
            if (! Str::startsWith($method, 'set') || ! Str::endsWith($method, 'Default')) {
                continue;
            }
            $attributeName = $this->getDefaultSetterAttributeName($method);
            $attributeValue = $this->{$method}();
            $this->setAttribute($attributeName, $attributeValue);
        }
    }

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        // Set the field default attributes in here.

        // $this->something('value');
        // or:
        // $this->setAttribute('something', 'value');
    }

    /**
     * Get attribute name from setter method name.
     *
     * setNameDefault => name
     * setCamelCaseDefault => camelCase
     *
     * @param  string $method
     * @return string
     */
    protected function getDefaultSetterAttributeName(string $method)
    {
        return lcfirst(
            Str::replaceFirst(
                'set',
                '',
                Str::replaceLast(
                    'Default',
                    '',
                    $method
                )
            )
        );
    }

    /**
     * Get avaliable slots.
     *
     * @return array
     */
    public function getAvailableSlots()
    {
        return $this->availableSlots;
    }

    /**
     * Get required attributes.
     *
     * @return array
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Render field.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge($this->attributes, $this->props);
    }

    /**
     * Get attribute.
     *
     * @param  string $name
     * @return any
     */
    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Get attribute.
     *
     * @param  string $name
     * @return any
     */
    public function __set(string $name, $value)
    {
        return $this->setAttribute($name, $value);
    }

    /**
     * Call field method.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (FieldDependency::conditionExists($method)) {
            return $this->addDependency(
                FieldDependency::make($method, ...$this->resolveDependencyArguments(...$parameters))
            );
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }
}
