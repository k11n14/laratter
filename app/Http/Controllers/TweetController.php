<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Tweet;

use Illuminate\Http\Request;

// ð½ è¿½å 
use Auth;
// ï¼ï¼ï¼use Illuminate\Support\Facades\Auth;

// ð½ è¿½å 
use App\Models\User;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // ð½ ç·¨é
        $tweets = Tweet::getAllOrderByUpdated_at();
        return view('tweet.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tweet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // ããªãã¼ã·ã§ã³
        $validator = Validator::make($request->all(), [
            'tweet' => 'required | max:191',
            'description' => 'required',
        ]);
        // ããªãã¼ã·ã§ã³:ã¨ã©ã¼
        if ($validator->fails()) {
            return redirect()
                ->route('tweet.create')
                ->withInput()
                ->withErrors($validator);
        }
        // create()ã¯æåããç¨æããã¦ããé¢æ°
        // æ»ãå¤ã¯æ¿å¥ãããã¬ã³ã¼ãã®æå ±
        // $result = Tweet::create($request->all());

        // ð½ ç·¨é ãã©ã¼ã ããéä¿¡ããã¦ãããã¼ã¿ã¨ã¦ã¼ã¶IDããã¼ã¸ãï¼DBã«insertãã
        $data = $request->merge(['user_id' => Auth::user()->id])->all();
        $result = Tweet::create($data);

        // ã«ã¼ãã£ã³ã°ãtodo.indexãã«ãªã¯ã¨ã¹ãéä¿¡ï¼ä¸è¦§ãã¼ã¸ã«ç§»åï¼
        return redirect()->route('tweet.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $tweet = Tweet::find($id);
        return view('tweet.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $tweet = Tweet::find($id);
        return view('tweet.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //ããªãã¼ã·ã§ã³
        $validator = Validator::make($request->all(), [
            'tweet' => 'required | max:191',
            'description' => 'required',
        ]);
        //ããªãã¼ã·ã§ã³:ã¨ã©ã¼
        if ($validator->fails()) {
            return redirect()
                ->route('tweet.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }
        //ãã¼ã¿æ´æ°å¦ç
        $result = Tweet::find($id)->update($request->all());
        return redirect()->route('tweet.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $result = Tweet::find($id)->delete();
        return redirect()->route('tweet.index');
    }

    // çç¥

    public function mydata()
    {
        // Userã¢ãã«ã«å®ç¾©ãããªã¬ã¼ã·ã§ã³ãä½¿ç¨ãã¦ãã¼ã¿ãåå¾ããï¼
        $tweets = User::query()
            ->find(Auth::user()->id)
            ->userTweets()
            ->orderBy('created_at', 'desc')
            ->get();
        return view('tweet.index', compact('tweets'));
    }
}
