<?php

namespace Lyhty\Ownership;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class OwnershipRule extends Rule
{
    protected Model $model;
    protected ?string $foreignKey;
    protected $ownerId;

    public function __construct($model, string $foreignKey = null, $ownerId = null)
    {
        $this->model = $model instanceof Model ? $model : new $model;
        $this->foreignKey = $foreignKey;
        $this->ownerId = $ownerId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $foreignKey = $this->foreignKey ??= $this->getForeignKey();
        $ownerId = $this->ownerId ??= auth()->id();

        return $ownerId && $this->model->query()
            ->whereKey($value)
            ->where($foreignKey, $ownerId)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute is not owned by the specified owner.";
    }
}