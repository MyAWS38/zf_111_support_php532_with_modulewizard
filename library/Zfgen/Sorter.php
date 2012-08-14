<?php
/**
 * Array Assoc library
 *
 * @copyright   by Abdul Malik Ikhsan
 * @license     GPL (http://www.gnu.org/licenses/gpl.html)
 * @author      Abdul Malik Ikhsan
 * @link        http://samsonasik.wordpress.com
 *
 * @package     Zfgen
 */

class Zfgen_Sorter
{   
    public function Zfgen_Sorter(){ }
    
    /* public function sortingAssoc($array, $sortby = "id",$sortir="asc")
    {
        $temp = array();
        foreach($array as $key=>$row)
        { 
            $temp[$key] = $row[$sortby];
        }
        
        if ($sortir=="asc") sort($temp); else rsort($temp);
        
        $loop = 0;
        foreach($temp as $key=>$row)
        {
            foreach($array as $keydata=>$rowdata)
            {
                if ($temp[$loop]==$rowdata[$sortby]){
                    $temp[$loop] = $rowdata;
                    $loop++;
                }
            }
        }
        
        return $temp; 
    
    }*/
    
    public function sortingAssoc($array, $sortby = "id",$sortir="asc")
    {
        $temp = array();
        foreach($array as $key=>$row)
        { 
            $temp[] = $row[$sortby];
        }
        
        if ($sortir=="asc") sort($temp); else rsort($temp);
        	
        $arResult = array();
        foreach($temp as $key=>$row)
        {
        	foreach($array as $keydata=>$rowdata)
        	{
        		if ($rowdata[$sortby]==$row)
        			$arResult[] = $rowdata;
        	}        		
        }               
               
        return $arResult;     
    }
}
    