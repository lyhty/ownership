<?php

namespace Lyhty\Ownership;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use RuntimeException;

class OwnershipRule extends Rule
{
    protected Model $model;

    protected $owner;

    protected ?string $foreignKey;

    /**
     * The rule constructor.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Illuminate\Database\Eloquent\Model|string|int  $owner
     * @param  string|null  $foreignKey  Required if owner argument is not a Model instance.
     */
    public function __construct($model, $owner, string $foreignKey = null)
    {
        $this->model = $model instanceof Model ? $model : new $model;
        $this->foreignKey = $foreignKey;

        if ($owner instanceof Model) {
            $this->owner = $owner->getKey();
            $this->foreignKey ??= $owner->getForeignKey();
        } else {
            $this->owner = $owner;
        }

        if (is_null($this->foreignKey)) {
            throw new RuntimeException('Foreign key could not be resolved.');
        }
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
        $foreignKey = $this->foreignKey;
        $owner = $this->owner ??= auth()->id();

        return $owner && $this->model->query()
            ->whereKey($value)
            ->where($foreignKey, $owner)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not owned by the specified owner.';
    }
}
