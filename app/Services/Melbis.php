<?php

if ( !function_exists('MELBIS') ) 
{
    function MELBIS() 
    {
        return \App\Services\MelbisLogic::getParser();
    }
}