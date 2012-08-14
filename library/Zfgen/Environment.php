<?php
/**
 * System Environment library
 *
 * @copyright   by Abdul Malik Ikhsan
 * @license     GPL (http://www.gnu.org/licenses/gpl.html)
 * @author      Abdul Malik Ikhsan
 * @link        http://samsonasik.wordpress.com
 *
 * @package     Zfgen
 */


class Zfgen_Environment
{
    public function Zfgen_Environment()
    {
        
    }
    
    public function getServerSignature()
    {
        $os_sign = $_SERVER['SERVER_SIGNATURE'];
        $explode_os = explode("Win32",$os_sign);
        
        return  ( count($explode_os) > 1) ? "windows" : "unix";        
    }   
    
} 
