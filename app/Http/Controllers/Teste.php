<?php

namespace App\Http\Controllers;

use App\Cnab;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste()
    {
        $string = 'ÁÍÓÚÉÄÏÖÜËÀÌÒÙÈÃÕÂÎÔÛÊáíóúéäïöüëàìòùèãõâîôûêÇçýÝñÑ';
        //Substitua pela string que desejas converter
        return preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $string));
        //Irá exibir "AIOUEAIOUEAIOUEAOAIOUEaioueaioueaioueaoaioueCc"

        // $teste = Cnab::teste();

        // $file = "ação-íaaa.jpg";
        // $file = iconv('UTF-8', 'ASCII//TRANSLIT', $file);
        // return "{$file} <br>";

        // setlocale(LC_ALL, 'pt_BR');
        // $file = iconv('UTF-8', 'ASCII//TRANSLIT', 'aáeé');
        // return "{$file} <br>";

        $string = "olá à mim! ñ";

        return preg_replace(
            array(
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ç)/",
                "/(Ç)/",
                "/(ñ)/",
                "/(Ñ)/",
                "/(ý)/",
                "/(Ý)/"
            ),
            explode(" ", "a A e E i I o O u U c C n N y Y"),
            $string
        );
    }
}
