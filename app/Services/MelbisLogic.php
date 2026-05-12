<?php

namespace App\Services;

use Exception;
use Melbis\MelbisShop\MySql;
use Melbis\MelbisShop\Parser;

class MelbisLogic
{
    private static ?Parser $parser = null;

    public function __construct()
    {
        $this->loadConstants();
        $this->initializeMelbis();

        MELBIS()->Include('melbis_inc_logic.php');
    }

    private function loadConstants(): void
    {
        $configPath = base_path('../config.json');        
        if (file_exists($configPath)) 
        {
            $config = json_decode(file_get_contents($configPath), true) ?? [];
            foreach ($config as $const => $value) 
            {
                if ( !defined($const) ) 
                {
                    define($const, $value);
                }
            }
        }
    }

    private function initializeMelbis(): void
    {
        if ( self::$parser !== null ) 
        {
            return;
        }
        $error_halt = [self::class, 'halt'];

        $db = new MySql($error_halt);
        $db->Connect(__FILE__, __LINE__); 
                                            
        self::$parser = new Parser($error_halt, $db);
    }

    public static function getParser(): Parser
    {
        if (self::$parser === null) 
        {
            throw new Exception("Melbis Shop no ready!");
        }

        return self::$parser;
    }    


    public function call($functionName, $params = [])
    {        
        if ( !is_callable($functionName) ) 
        {
            throw new Exception("Function core {$functionName} not found!");
        }

        return call_user_func_array($functionName, $params);
    }

 
    public static function halt($mType, $mFile, $mError, $mInfo = '')
    {
        $message = "Melbis Error [$mType] in $mFile: $mError";
        
        if (!empty($mInfo)) 
        {
            $message .= " | Info: " . trim($mInfo);
        }
            
        throw new Exception($message);
    }    
}