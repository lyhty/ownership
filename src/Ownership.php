<?php

namespace Lyhty\Ownership;

use Illuminate\Database\Eloquent\Model;

/**
 * @method string getForeignKey()
 * @method mixed getKey()
 */
trait Ownership
{
    /**
     * Return boolean value whether the model instance owns the given Model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $foreignKey
     * @return bool
     */
    public function owns(Model $model, ?string $foreignKey = null): bool
    {
        $foreignKey ??= $this->getForeignKey();

        return $this->getKey() == $model->getAttribute($foreignKey);
    }

    /**
     * Return boolean value whether the model instance doesn't own the given Model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $foreignKey
     * @return bool
     */
    public function doesntOwn(Model $model, ?string $foreignKey = null): bool
    {
        return ! $this->owns($model, $foreignKey);
    }
}
