<?php

namespace AwStudio\Fjord\TrackEdits;

trait TrackEdits
{
    /**
     * Track edit on update.
     *
     * @param array $attributes
     * @param array $options
     */
    public function update(array $attributes = [], array $options = [])
    {
        $update = parent::update($attributes, $options);

        if (!fjord_user()) {
            return $update;
        }

        $edit = new ModelEdit();
        $edit->model_type = static::class;
        $edit->model_id = $this->id;
        $edit->fjord_user_id = fjord_user()->id;
        $edit->created_at = \Carbon\Carbon::now();
        $edit->save();

        return $update;
    }

    /**
     * Get last edit.
     *
     * @return morphOne
     */
    public function last_edit()
    {
        return $this->morphOne('AwStudio\Fjord\TrackEdits\ModelEdit', 'model')
            ->orderByDesc('id')
            ->with('user');
    }
}
