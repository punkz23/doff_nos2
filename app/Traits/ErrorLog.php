<?php

namespace App\Traits;

use App\RecordLogs\ErrorLogs;

use App\Class\DOFFClass;
use Auth;

trait ErrorLog
{
    function error_log($e,$url){
        $functions = new DOFFClass();
        $errorId = $functions->createREFNumber('ER');

        ErrorLogs::create([
            'error_id' => $errorId,
            'error_message' => $e->getMessage(),
            'error_description' => $e->__toString(),
            'encountered_by' => Auth::user()->name,
            'request_url' => $url,
            'assumed_module' => session('module'),
            // Additional fields as needed
        ]);

        session(['errorId' => $errorId]);
        session(['errorDescription' => $e->getMessage()]);
    }
}
?>
