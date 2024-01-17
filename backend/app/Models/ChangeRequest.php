<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ChangeRequest extends Model implements Auditable
{
    use HasFactory;
    use BaseModel;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['status', 'source', 'started_by_id', 'changes', 'drug_id', 'viewed', 'message'];

    public static function getValidationRules(array $values, self $model = null)
    {
        $rc = [
            'status' => ['required', 'in:PENDING'],
            'source' => ['required', 'in:USER,NOC,DPD'],
            'changes' => ['required', 'array'],
            'message' => ['nullable', 'string'],
            'drug_id' => ['nullable', 'exists:drugs,id'],
        ];

        if ($model) {
            $rc['status'] = ['required', 'in:PENDING,DECLINE,APPROVE'];
        }

        return $rc;
    }

    public function setChangesAttribute($value)
    {
        $this->attributes['changes'] = json_encode($value);
    }

    public function getChangesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function cleanChanges()
    {
        $disorders = [];
        $changes = $this->getAttribute('changes');

        if ($changes['disorders'] ?? false) {
            $disorders = $changes['disorders'];
            unset($changes['disorders'], $changes['disorders_names']);
        }

        if ($this->drug_id) {
            $drug = Drug::find($this->drug_id);
            foreach ($drug->getAttributes() as $k => $v) {
                if (!array_key_exists($k, $changes)) {
                    continue;
                }

                if (is_string($changes[$k])) {
                    $changes[$k] = trim($changes[$k]);
                }

                if (in_array($k, ['created_at', 'updated_at'])) {
                    unset($changes[$k]);
                } elseif (in_array($v, [null, '']) && in_array($changes[$k], [null, ''])) {
                    unset($changes[$k]);
                } elseif ($changes[$k] == $v) {
                    unset($changes[$k]);
                }
            }
            if ($disorders) {
                $old_disorders = $drug->disorders->pluck('id')->toArray();
                if (array_diff($old_disorders, $disorders) || array_diff($disorders, $old_disorders)) {
                    $changes['disorders'] = $disorders;
                }
            }
        } else {
            foreach (array_keys($changes) as $k) {
                if (is_string($changes[$k])) {
                    $changes[$k] = trim($changes[$k]);
                }
                if (!$changes[$k]) {
                    unset($changes[$k]);
                }
            }

            if ($disorders) {
                $changes['disorders'] = $disorders;
            }
        }

        $this->setAttribute('changes', $changes);
    }

    public function approve($message = '')
    {
        $changes = $this->getAttribute('changes');
        $drug = Drug::firstOrNew(['id' => $this->drug_id]);
        $drug->fill($changes);
        $drug->save();
        if ($changes['disorders'] ?? false) {
            $old_disorders = $drug->disorders->pluck('id')->toArray();
            $new_disorders = $changes['disorders'];
            $drug->disorders()->syncWithoutDetaching($new_disorders ?? []);
            $drug->disorders()->detach(array_diff($old_disorders, $new_disorders));
        }

        $this->status = 'APPROVED';
        $this->message = $message;
        $this->save();
    }

    public function decline($message = '')
    {
        $this->status = 'DECLINED';
        $this->message = $message;
        $this->save();
    }
}
