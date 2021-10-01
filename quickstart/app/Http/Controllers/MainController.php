<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function getHtml()
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $html = file_get_contents('http://apple.com', false, stream_context_create($arrContextOptions)); // get html code

        $html = substr($html, strpos($html, '<body')); // delete <head>

        $html = new \Html2Text\Html2Text($html); // get words

        $words = preg_replace('/(\[)(.*?)(\])/', "", $html->getText()); // delete all path, for example [folder/folder]
        $words = preg_replace('/\* /', "", $words); // delete all *
        $words = preg_replace('/[^a-zA-Z ]/', "", $words); // delete all numbers and punctuation marks
        $words = preg_replace('/\s+/', ' ', $words); // delete all repeating spaces

        $set = mb_strtolower(trim($words)); // make all letters lower
        $array = array_count_values(explode(' ', $set)); // create array and count words quantity
        arsort($array); // sort array

        return view('welcome', ['html' => $array]);
    }
}
