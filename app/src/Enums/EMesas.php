<?php

namespace Enums;

class EMesas 
{
    const MESA1 = 1;
    const MESA2 = 2;
    const MESA3 = 3;
    const MESA4 = 4;
    const MESA5 = 5;
    const MESA6 = 6;
    const MESA7 = 7;
    const MESA8 = 8;
    const MESA9 = 9;
    const MESA10 = 10;
    //const MESAS = array(1,2,3,4,5,6,7,8,9,10);//cantidad de mesas que se pueden usar
    const MESAS = array(1,2,3,4,5);

    public static function GetDescription($numero)
    {
        switch ($numero) {
            case EMesas::MESA1:
                return "MESA1";
            case EMesas::MESA2:
                return "MESA2";
            case EMesas::MESA3:
                return "MESA3";
            case EMesas::MESA3:
                return "MESA3";
            case EMesas::MESA4:
                return "MESA4";
            case EMesas::MESA5:
                return "MESA5";
            case EMesas::MESA6:
                return "MESA6";
            case EMesas::MESA7:
                return "MESA7";
            case EMesas::MESA8:
                return "MESA8";                        
            case EMesas::MESA9:
                return "MESA9";
            case EMesas::MESA10;
                return "MESA10";
        }
    }
    public static function getVal($string)
    {
        switch ($string) {
            case 'MESA1':
                return EMesas::MESA1;
            case 'MESA2':
                return EMesas::MESA2;
            case 'MESA3':
                return EMesas::MESA3;
            case 'MESA4':
                return EMesas::MESA4;
            case 'MESA5':
                return EMesas::MESA5;
            case 'MESA6':
                return EMesas::MESA6;
            case 'MESA7':
                return EMesas::MESA7;
            case 'MESA8':
                return EMesas::MESA8;
            case 'MESA9':
                return EMesas::MESA9;
            case 'MESA10':
                return EMesas::MESA10;
            default:
                return "";
        }
    }
}