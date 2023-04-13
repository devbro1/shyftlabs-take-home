<?php

namespace App\Http\Controllers;

class SystemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/phpinfo/",
     *     summary="get phpinfo() output",
     *     tags={"System"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function phpinfo()
    {
        //  phpinfo();
        $rc = [];
        $rc['version'] = phpversion();
        $rc['system'] = php_uname();
        $rc['build'] = php_sapi_name();
        $rc['memory']['usage'] = memory_get_usage();
        $rc['memory']['peak'] = memory_get_peak_usage();
        $rc['ini']['files'] = php_ini_loaded_file();
        $rc['ini']['scanned_files'] = php_ini_scanned_files();
        $rc['gc']['status'] = gc_status();
        $rc['zend_version'] = zend_version();
        $rc['extensions'] = [];
        $extensions = get_loaded_extensions();
        foreach ($extensions as $ext) {
            $rc['extensions'][$ext]['version'] = phpversion($ext);

            try {
                $rc['extensions'][$ext]['ini'] = $rc['ini'] = ini_get_all($ext);
            } catch (\Exception $e) {
            }
        }

        $rc['variables']['_SERVER'] = $_SERVER ?? null;
        $rc['variables']['_GET'] = $_GET ?? null;
        $rc['variables']['_POST'] = $_POST ?? null;
        $rc['variables']['_FILES'] = $_FILES ?? null;
        $rc['variables']['_REQUEST'] = $_REQUEST ?? null;
        $rc['variables']['_SESSION'] = $_SESSION ?? null;
        $rc['variables']['_ENV'] = $_ENV ?? null;
        $rc['variables']['_COOKIE'] = $_COOKIE ?? null;
        $rc['variables']['php_errormsg'] = $php_errormsg ?? null;
        $rc['variables']['http_response_header'] = $http_response_header ?? null;
        $rc['variables']['argc'] = $argc ?? null;
        $rc['variables']['arg'] = $arg ?? null;

        // to resolve NAN to json error
        foreach (get_defined_constants(true) as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $rc['constants'][$k1][$k2] = (string) $v2;
            }
        }

        return $rc;
    }
}
