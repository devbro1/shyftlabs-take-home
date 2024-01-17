<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\File;
use App\Models\Drug;
use App\Models\Translation;
use App\Models\Disorder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Actions\Database\GetTableDetails;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportFileToTable implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $file_id;
    public $table_name;

    /**
     * Create a new job instance.
     *
     * @param mixed $table_name
     * @param mixed $file_id
     */
    public function __construct($table_name, $file_id)
    {
        $this->file_id = $file_id;
        $this->table_name = $table_name;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info(sprintf('processing table %s using file_id %d', $this->table_name, $this->file_id));
        $file = File::where(['id' => $this->file_id])->first();

        if ('xlsx' === $file->extension) {
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getFileAbselutePath());

            $writer = IOFactory::createWriter($spreadsheet, 'Csv');
            $writer->setSheetIndex(0);
            $writer->setDelimiter(',');

            $tempFilePath = tempnam(sys_get_temp_dir(), 'tempfile_').'.csv';
            $writer->save($tempFilePath);
            Log::info('finished conversion from xlsx to csv');
            $csv = Reader::createFromPath($tempFilePath);
        } else {
            $csv = Reader::createFromPath($file->getFileAbselutePath());
        }

        $csv->setHeaderOffset(0);

        $headers = $csv->getHeader();
        $db_columns = \Schema::getColumnListing($this->table_name);
        $db_columns_details = GetTableDetails::run($this->table_name);
        $fre_fields = [];
        foreach (Drug::getFieldsData() as $drug_field) {
            if (!empty($drug_field['db_col_translation_of'])) {
                $fre_fields[$drug_field['excel_col_name']] = $drug_field['db_col_name'];
            }
        }

        if ('drugs' == $this->table_name) {
            $drug_fields = Drug::getFieldsData();
            foreach ($headers as &$header) {
                foreach ($drug_fields as $field) {
                    if ($field['excel_col_name'] == $header) {
                        $header = $field['db_col_name'];
                    }
                }
            }
        } else {
            foreach ($headers as &$header) {
                $header = trim($header);
                if (!empty($fre_fields[$header])) {
                    $header = $fre_fields[$header];

                    break;
                }

                $header = strtolower($header);
                $header = str_replace(' ', '_', $header);
                $header = str_replace('-', '_', $header);
                $header = str_replace('/', '_', $header);
                $header = str_replace('_(y_n_sa)', '', $header);
                $header = str_replace('_y_n', '', $header);
                $header = str_replace('_(y_n)', '', $header);
                $header = str_replace('(y_n)', '', $header);
            }
        }

        if ('drugs' == $this->table_name) {
            $drug_fields = Drug::getFieldsData();
            foreach ($headers as &$header) {
                foreach ($drug_fields as $field) {
                    if ($field['excel_col_name'] == $header) {
                        $header = $field['db_col_name'];
                    }
                }
            }
        } else {
            foreach ($headers as &$header) {
                $header = trim($header);
                if (!empty($fre_fields[$header])) {
                    $header = $fre_fields[$header];

                    break;
                }

                $header = strtolower($header);
                $header = str_replace(' ', '_', $header);
                $header = str_replace('-', '_', $header);
                $header = str_replace('/', '_', $header);
                $header = str_replace('_(y_n_sa)', '', $header);
                $header = str_replace('_y_n', '', $header);
                $header = str_replace('_(y_n)', '', $header);
                $header = str_replace('(y_n)', '', $header);
            }
        }

        $rows = $csv->getRecords($headers);

        DB::table($this->table_name)->truncate();
        foreach ($rows as $row) {
            foreach ($row as $k => &$v) {
                if (!in_array($k, $db_columns)) {
                    unset($row[$k]);
                } elseif ('boolean' == $db_columns_details['columns'][$k]['type']) {
                    if (in_array($v, ['Y', 'YES', 'yes', 'Yes', 'On', 'on', 'ON', '1'])) {
                        $v = true;
                    } else {
                        $v = false;
                    }
                }
            }

            $row['created_at'] = $row['updated_at'] = Carbon::now()->subDays(30);
            DB::table($this->table_name)->insert($row);
        }
        Log::info('finished inserting');

        if ('drugs' == $this->table_name) {
            $this->extractTranslationFromDrugs();
            Log::info('finished extractTranslationFromDrugs');
            DB::table('disorder_drug')->truncate();
            $this->extractDisordersFromDrugs();
            Log::info('finished extractDisordersFromDrugs');
            Drug::where('din', '!=', '')->update(['din' => DB::raw("LPAD(din, 8, '0')"), 'din_pin' => DB::raw("LPAD(din_pin, 8, '0')")]);
        }

        Log::info(sprintf('Finished processing table %s using file_id %d successfully', $this->table_name, $this->file_id));
    }

    public function extractTranslationFromDrugs()
    {
        $translated_fields = Drug::getFrenchFields();
        Translation::where('namespace', 'drug')->delete();

        $path = 'public/'.Str::kebab('translations').'.csv';
        // $full_path = Storage::path($path);
        // $writer = Writer::createFromPath($full_path, 'w+');
        // $writer->insertOne(['English', 'French']);

        foreach (Drug::all() as $drug) {
            foreach ($translated_fields as $fields) {
                if (!$drug->{$fields['db_col_name']} || !$drug->{$fields['db_col_name_french']}) {
                    continue;
                }
                if ('strength_french' == $fields['db_col_name_french']) {
                    continue;
                }

                // $writer->insertOne([$drug->{$fields['db_col_name']}, $drug->{$fields['db_col_name_french']}]);

                $translation = Translation::where('namespace', 'drug')->where('key', $drug->{$fields['db_col_name']})->first();
                if ($translation) {
                    continue;
                }

                $translation = new Translation();
                $translation->key = $drug->{$fields['db_col_name']};
                $translation->translation = $drug->{$fields['db_col_name_french']};
                $translation->namespace = 'drug';
                $translation->language = 'fr';
                $translation->save();

                // Log::info('added translation:'.$translation->key);
            }
        }
    }

    public function extractDisordersFromDrugs()
    {
        foreach (Drug::all() as $drug) {
            if (!$drug->medical_condition) {
                continue;
            }
            $disorder = Disorder::where('category', $drug->medical_condition)->where('name', ($drug->sub_medical_condition) ? $drug->sub_medical_condition : $drug->medical_condition)->first();
            if (!$disorder) {
                $disorder = new Disorder();
                $disorder->category = $drug->medical_condition;
                $disorder->name = ($drug->sub_medical_condition) ? $drug->sub_medical_condition : $drug->medical_condition;
                $disorder->save();

                Log::info('created disorder: '.$disorder->name);
            }

            if ($drug->disorders->pluck('id')->contains($disorder->id)) {
                // do nothing
            } elseif ('D' === $drug->din_duplicate) {
                $correct_drug = Drug::where('din_duplicate', '!=', 'D')->where('din_pin', $drug->din_pin)->first();
                $correct_drug->disorders()->syncWithoutDetaching($disorder->id);
            } else {
                $drug->disorders()->attach($disorder->id);
                $drug->medical_condition = '';
                $drug->sub_medical_condition = '';
                $drug->save();
            }
        }

        Drug::where('din_duplicate', 'D')->delete();
    }

    public function failed(\Error $exception)
    {
        Log::error('JOB FAILED: '.$exception->getMessage());
        Log::error($exception);
    }
}
