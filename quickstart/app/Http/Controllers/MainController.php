<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\RequestResult;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        return view('index', ['links' => Link::all()]);
    }

    public function showAllResults(Link $link)
    {
        return view('showAll', ['requests' => $link->requests, 'link' => $link]);
    }

    public function store(Request $request)
    {
        Link::create([
                'url' => $request->url,
                'notes' => $request->notes ?: null
        ]);

        return $this->index();
    }

    public function makeRequests(Request $request)
    {
        foreach ($request->links as $link)
        {
            $res = self::getWords('http://'.Link::find($link)->url);

            RequestResult::create([
                'link_id' => $link,
                'test_number' => 1 ?: null,
                'words' => $res
            ]);
        }

        return $this->index();
    }

    public static function getWords($url): string
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        // url = http://apple.com
        $html = file_get_contents($url, false, stream_context_create($arrContextOptions)); // get html code

        $html = substr($html, strpos($html, '<body')); // delete <head>

        $html = new \Html2Text\Html2Text($html); // get words

        $words = preg_replace('/(\[)(.*?)(\])/', "", $html->getText()); // delete all path, for example [folder/folder]
        $words = preg_replace('/\* /', "", $words); // delete all *
        $words = preg_replace('/[^a-zA-Z ]/', "", $words); // delete all numbers and punctuation marks
        $words = preg_replace('/\s+/', ' ', $words); // delete all repeating spaces

        $set = mb_strtolower(trim($words)); // make all letters lower
        $array = array_count_values(explode(' ', $set)); // create array and count words quantity
        arsort($array); // sort array

        $total = array_sum($array);
        foreach($array as $key => $elem)
        {
            $array[$key] = round($elem / $total,2) ;
        }

        return serialize(array_slice($array, 0, 20));
        //return view('welcome', ['html' => array_slice($array, 0, 20)]);
    }
}
