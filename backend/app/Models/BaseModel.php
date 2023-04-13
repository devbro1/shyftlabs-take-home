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

    /**
     * @deprecated
     *
     * @param mixed $params
     */
    public static function querySearch($params = [], array $search_conditions = [])
    {
        $rc = self::query();
        $search_conditions = array_merge((new self())->search_conditions ?? [], $search_conditions);

        foreach ($params as $k => $v) {
            if (!$v) {
                continue;
            }
            if (in_array($k, ['order_by'])) {
                // skip
            } elseif (is_array($v)) {
                $rc->whereIn($k, $v);
            } elseif (!isset($search_conditions[$k])) {
                continue;
            } elseif ('startsWith' == $search_conditions[$k]) {
                $rc->where($k, 'ilike', $v.'%');
            } elseif ('endsWith' == $search_conditions[$k]) {
                $rc->where($k, 'ilike', '%'.$v);
            } elseif ('contains' == $search_conditions[$k]) {
                $rc->where($k, 'ilike', '%'.$v.'%');
            } elseif ('equals' == $search_conditions[$k]) {
                $rc->where($k, $v);
            } elseif ('between' == $search_conditions[$k]) {
                $m = null;
                preg_match('/^(.*),(.*)$/', $v, $m);
                [,$from,$to] = $m;
                $rc->whereBetween($k, [$from, $to])->get();
            }
        }

        if ($params['order_by'] ?? false) {
            $direction = 'asc';
            $field = $params['order_by'];
            if (str($params['order_by'])->endsWith(' asc')) {
                $field = str($field)->rtrim(' asc');
            } elseif (str($params['order_by'])->endsWith(' desc')) {
                $direction = 'desc';
                $field = str($field)->substr(0, str($field)->length() - 5);
            }
            $rc->orderBy($field, $direction);
        }

        return $rc;
    }
}
