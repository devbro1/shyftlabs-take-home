<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HasPermission implements Rule
{
    /**
     * Create a new rule instance.
     */
    private $model = '';
    private $permission = '';

    public function __construct($model, $permission)
    {
        $this->model = $model;
        $this->permission = $permission;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $m = $this->model::find($value);
        if (empty($m)) {
            return false;
        }

        return $m->can($this->permission);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'user id :attribute does not have permission';
    }
}
