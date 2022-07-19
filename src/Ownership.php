<?php

namespace Lyhty\Ownership;

use Illuminate\Database\Eloquent\Model;

trait Ownership
{
    /**
     * Return boolean value whether the model instance owns given Model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @return bool
     */
    public function owns(Model $model, string $key): bool
    {
        $key ??= $this->getForeignKey();

        return $this->getKey() == $model->{$key};
    }
}