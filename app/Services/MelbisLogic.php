<?php

namespace App\Services;

use Exception;
use Melbis\MelbisShop\MySql;
use Melbis\MelbisShop\Parser;

class MelbisLogic
{

    // Create
    public function __construct()
    {
        if ( !isset($GLOBALS['gParser']) ) 
        {                        
            global $gParser;

            // Melbis constants
            $config = json_decode(file_get_contents(base_path('../config.json')), true);    
            foreach( $config as $const => $value)
            {
                define($const, $value);
            }

            // Halt function
            $error_halt = [$this, 'halt'];

            // Connect DB
            $db = new MySql($error_halt);
            $db->Connect(__FILE__, __LINE__);

            // Create Parser
            $gParser = new Parser($error_halt, $db);            
            $gParser->Include('melbis_inc_logic.php');
        }
    }

    // Call melbis core function
    public function call($functionName, $params = [])
    {        
        if ( !is_callable($functionName) ) 
        {
            throw new \Exception("Function core {$functionName} not found!");
        }

        $result = call_user_func_array($functionName, $params);

        return $result;
    }

 
    // Halt error
    public function halt($mType, $mFile, $mError, $mInfo = '')
    {
        $message = "Melbis Error [$mType] in $mFile: $mError";
        
        if ( !empty($mInfo) ) 
        {
            $message .= " | Info: " . trim($mInfo);
        }
            
        throw new \Exception($message);
    }      

}