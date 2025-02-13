<?php

namespace Tero\Services;

use Tero\Services\ConfigService;

enum LogType: string {
    case ERROR = 'ERROR';
    case INFO = 'INFO';
    case LOG = 'LOG';
    case WARNING = 'WARNING';
}

class LogService { 
    private ConfigService $configService; 
    private $log; 

    public function __construct(ConfigService $configService){ 
        $this->configService = $configService;
        $this->log = $this->configService->get('log'); 
    }

    public function log(LogType $type, string $data){
        if(!$this->log->enable) return;
        
        $fileName = $this->configService->replace($this->log->file, ['BASEPATH'=> $_ENV['BASEPATH'] ]);

        $date =  date($this->log->template->date, time());

        $template = $this->configService->replace($this->log->template->line, [
            'date'      => $date,
            'type'      => $type->value,
            'content'   => $data
        ]);

        file_put_contents( $fileName, $template, FILE_APPEND); 
    }

}