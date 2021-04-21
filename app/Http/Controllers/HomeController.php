<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();
        return view('home', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        User::create($request->all());
        return redirect('home');
    }

    public function edit($user_id)
    {
        $user = User::find($user_id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $user->update($request->all());
        return redirect('home');
    }

    public function destroy($user_id)
    {
        $user = User::find($user_id);
        $user->delete();
        return redirect('home');
    }

    public function rssData()
    {
        $news = [];
        $xmlString = file_get_contents('https://www.antaranews.com/rss/terkini.xml');
        $xmlObject = simplexml_load_string($xmlString);
        $json = json_encode($xmlObject);
        $array = json_decode($json);

        foreach ($array->channel->item as $value) {
          if ($request->has('search') && $request->search != '') {
            if (strpos($value->title, $request->search) !== false) {
              array_push($news, [
                'title' => $value->title,
                'published_date' => $value->pubDate,
                'link' => $value->link
              ]);
            }
          }else {
            array_push($news, [
              'title' => $value->title,
              'published_date' => $value->pubDate,
              'link' => $value->link
            ]);
          }
        }

        return response()->json(compact('news'));
    }

    public function rss(Request $request)
    {
        return view('rss');
    }
}
