<?php

namespace Lit\Crud\Fields;

use Closure;
use Lit\Crud\BaseField;
use Lit\Crud\BaseForm;

class Modal extends BaseField
{
    use Traits\FieldHasForm;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-modal';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['name', 'form'];

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->size('md');
        $this->variant('secondary');
        $this->confirmWithPassword(false);
    }

    /**
     * Set modal preview.
     *
     * @param string $variant
     *
     * @return $this
     */
    public function preview(string $preview)
    {
        $this->setAttribute('preview', $preview);

        return $this;
    }

    /**
     * Set modal variant.
     *
     * @param string $variant
     *
     * @return $this
     */
    public function variant(string $variant)
    {
        $this->setAttribute('variant', $variant);

        return $this;
    }

    /**
     * Set modal name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name)
    {
        $this->setAttribute('name', $name);

        return $this;
    }

    /**
     * Set modal window size.
     *
     * @param string $size
     *
     * @return $this
     */
    public function size(string $size)
    {
        $this->setAttribute('size', $size);

        return $this;
    }

    /**
     * Set confirmWithPassword.
     *
     * @param bool $confirmWithPassword
     *
     * @return $this
     */
    public function confirmWithPassword(bool $confirmWithPassword = true)
    {
        $this->setAttribute('confirmWithPassword', $confirmWithPassword);

        return $this;
    }

    /**
     * Add form to modal.
     *
     * @param Closure $closure
     *
     * @return void
     */
    public function form(Closure $closure)
    {
        $form = new BaseForm($this->model);

        $form->setRoutePrefix(
            "$this->route_prefix/modal"
        );

        $form->registered(function ($field) {
            $field->setAttribute('params', [
                'field_id' => $this->id,
            ]);
        });

        $closure($form);

        if ($this->confirmWithPassword) {
            $form->password('__p_confirm')
                ->title('Password')
                ->confirm();
        }

        $this->setAttribute('form', $form);

        return $this;
    }
}
