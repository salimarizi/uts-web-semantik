<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RSSController extends Controller
{
    public function index()
    {
        return view('rss');
    }

    public function rssData(Request $request)
    {
        $news = [];

        $news = array_merge($news, $this->parsingRSS('antara', 'https://www.antaranews.com/rss/terkini.xml', $request));
        $news = array_merge($news, $this->parsingRSS('sindonews', 'https://www.sindonews.com/feed', $request));
        $news = array_merge($news, $this->parsingRSS('tribunnews', 'https://www.tribunnews.com/rss', $request));

        return response()->json(compact('news'));
    }

    public function parsingRSS($source, $link, Request $request)
    {
        $news = [];
        $xmlString = file_get_contents($link);
        $xmlObject = simplexml_load_string($xmlString);
        $json = json_encode($xmlObject);
        $array = json_decode($json);

        foreach ($array->channel->item as $value) {
          if ($request->has('search') && $request->search != '') {
            if (strpos($value->title, $request->search) !== false) {
              array_push($news, [
                'sumber' => $source,
                'title' => $value->title,
                'published_date' => $value->pubDate,
                'link' => $value->link
              ]);
            }
          }else {
            array_push($news, [
              'sumber' => $source,
              'title' => $value->title,
              'published_date' => $value->pubDate,
              'link' => $value->link
            ]);
          }
        }

        return $news;
    }
}
