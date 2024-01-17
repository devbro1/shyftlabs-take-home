<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;

trait BaseModel
{
    use HasFactory;

    public static function validate(self|\Illuminate\Http\Request|array $to_validate, self $model = null)
    {
        $values = [];
        if (is_object($to_validate) && $to_validate instanceof \Illuminate\Http\Request) {
            $values = $to_validate->all();
        } elseif (is_object($to_validate)) {
            $values = $to_validate->getAttributes();
        } elseif (is_array($to_validate)) {
            $values = $to_validate;
        }

        $rules = self::getValidationRules($values, $model);
        $validator = Validator::make($values, $rules);

        return $validator->validate();
    }

    abstract public static function getValidationRules(array $values, self $model = null);
}
