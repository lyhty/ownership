<?php

namespace Lyhty\Ownership;

use Illuminate\Database\Eloquent\Model;

trait Ownership
{
    /**
     * Return boolean value whether the model instance owns the given Model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $key
     * @return bool
     */
    public function owns(Model $model, string $key = null): bool
    {
        $key ??= $this->getForeignKey();

        return $this->getKey() == $model->{$key};
    }

    /**
     * Return boolean value whether the model instance doesn't own the given Model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $key
     * @return bool
     */
    public function doesntOwn(Model $model, string $key = null): bool
    {
        return ! $this->owns($model, $key);
    }
}
