<?php

class Zfgen_Convertdate
{
    private $bulan = array(
            '01'=>'Januari',
            '02'=>'Februari',
            '03'=>'Maret',
            '04'=>'April',
            '05'=>'Mei',
            '06'=>'Juni',
            '07'=>'Juli',
            '08'=>'Agustus',
            '09'=>'September',
            '10'=>'Oktober',
            '11'=>'November',
            '12'=>'Desember'
            );
    
    private $month = array(
            '01'=>'January',
            '02'=>'February',
            '03'=>'March',
            '04'=>'April',
            '05'=>'May',
            '06'=>'June',
            '07'=>'July',
            '08'=>'August',
            '09'=>'September',
            '10'=>'October',
            '11'=>'November',
            '12'=>'December'
        );
 
    public function samsonlibrary_Convertdate(){ }
    
    public function toIndonesian($date)
    {
        $explodeDate = explode("-",$date);
        $strDate = "";
        $strDate     .= $explodeDate[2];
        foreach($this->bulan as $key=>$bul)
        {
            if ($key==$explodeDate[1]){
                $strDate .=" ".$bul;
                break;
            }
        }
        $strDate .= " ".$explodeDate[0];
        
        return $strDate;
    }
    
    public function cutTime($date)
    {
        $str = str_replace(" 00:00:00","",$date);
        return $str;
    }
    
    public function toEnglish($date)
    {
        $explodeDate = explode("-",$date);
        $strDate = "";
        $strDate     .= $explodeDate[2];
        foreach($this->month as $key=>$month)
        {
            if ($key==$explodeDate[1]){
                $strDate .=" ".$month;
                break;
            }
        }
        $strDate .= " ".$explodeDate[0];
        
        return $strDate;
    }
    
    public function toEnglishNonVerbal($date)
    {
        $explodeDate = explode("-",$date);
        return $explodeDate[2]."-".$explodeDate[1]."-".$explodeDate[0];
    }
    
    public function dayIndonesianNow($day)
    {
        $hari = "";
        switch(strtolower($day))
        {
            case "monday":
                $hari="Senin";
                break;
            case "tuesday":
                $hari="Selasa";
                break;
            case "wednesday":
                $hari="Rabu";
                break;
            case "thursday":
                $hari="Kamis";
                break;
            case "friday":
                $hari="Jumat";
                break;
            case "saturday":
                $hari="Sabtu";
                break;
            case "sunday":
                $hari="Minggu";
                break;
        }
        
        return $hari;
    }
    
    public function dayIndonesian($date)
    {
        $explode = explode("-",$date);
        $date =  date("l", mktime(0, 0, 0, (int) $explode[1], (int) $explode[2], $explode[0]));
        
        return $this->dayIndonesianNow($date);
    }
    
}