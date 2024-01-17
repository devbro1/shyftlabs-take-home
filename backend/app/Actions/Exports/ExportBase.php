<?php

namespace App\Actions\Exports;

use Nick\SecureSpreadsheet\Encrypt;

class ExportBase
{
    public function SecureExcelFile($xls_full_path, $export)
    {
        if ($export->params['password'] ?? false) {
            $tmp_file_path = tempnam(sys_get_temp_dir(), 'tmp').'.xlsx';

            $test = new Encrypt();
            $test->input($xls_full_path)
                ->password($export->params['password'])
                ->output($tmp_file_path)
            ;

            copy($tmp_file_path, $xls_full_path);
        }
    }
}
