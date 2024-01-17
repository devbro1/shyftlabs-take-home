<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Export extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = ['name', 'status', 'action_class', 'message', 'file_id', 'duration', 'params'];

    public static $available_exports = [
        ['title' => 'All Drug Data', 'value' => 'ExportAll'],
        ['title' => 'Canada Life', 'value' => 'ExportCanadaLife'],
        ['title' => 'CLIC Master', 'value' => 'ExportCLICMaster'],
        ['title' => 'CLIC NexGen', 'value' => 'ExportCLICNexGen'],
        ['title' => 'CS Master', 'value' => 'ExportCSMaster'],
        ['title' => 'JG Master', 'value' => 'ExportJGMaster'],
        ['title' => 'SLF Master', 'value' => 'ExportSLFMaster'],
        ['title' => 'NOC N/A List', 'value' => 'ExportNOCNAs'],
        ['title' => 'DrugFinder New', 'value' => 'ExportDrugFinderNew'],
        ['title' => 'DrugFinder Update', 'value' => 'ExportDrugFinderUpdate'],
        ['title' => 'Export Translations', 'value' => 'ExportTranslations'],
        ['title' => 'Export Change Requests', 'value' => 'ExportChangeRequests'],
        ['title' => 'Export Din Mapping', 'value' => 'ExportDinMapping', 'fields' => ['file']],
    ];

    public static function getValidationRules(array $values, self $model = null)
    {
        $classes = [];

        foreach (Export::$available_exports as $opt) {
            $classes[] = $opt['value'];
        }

        return [
            'status' => ['required', 'in:PENDING'],
            'action_class' => ['required', 'in:'.implode(',', $classes)],
            'message' => ['nullable', 'string'],
            'file_id' => ['nullable', 'exists:files,id'],
            'duration' => ['nullable', 'number', 'min:0'],
            'params' => ['nullable', 'array'],
        ];
    }

    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value);
    }

    public function getParamsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function getVersion($date = 'now')
    {
        return Export::getStaticVersion($date);
    }

    public static function getStaticVersion($date = 'now')
    {
        $d1 = new \DateTime($date);
        $d2 = new \DateTime('2013-02-01');
        $Months = $d2->diff($d1);

        return ($Months->y * 12) + $Months->m;
    }
}
