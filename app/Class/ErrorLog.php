<?php
    namespace App\Class;

    use App\RecordLogs\ErrorLogs;
    use Auth;

    class ErrorLog
    {

        protected $doffClass;
        public function __construct()
        {
            $this->doffClass = new DOFFClass();
        }

        public function error_log($e,$url){

            $errorId = $this->doffClass->createREFNumber('ER');

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
