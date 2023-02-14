<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'password' => 'required',
        ]);

        $newPage = Page::create([
            'url_id' => base64_encode($request->question),
            'question' => $request->question,
            'password' => Hash::make($request->password),
        ]);

        // return $newPage;

        $page = Page::find($newPage->id);

        $page->url_id = base64_encode($request->question . $newPage->id);
        $page->save();
 
        return redirect()->route('page.show', [$page->url_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @param bool $loggedIn
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::firstWhere('url_id', $id);

        if (!$page) {
            return redirect()->route('page.index');
        }

        $words = $page->words;

        return view('page.show', [
            'id' => $id,
            'question' => $page->question,
            'loggedIn' => false,
            'words' => $words,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('page.show', [$id]);
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
        if (!AuthController::checkUserLoggedin($id)) return redirect()->route('page.show', [$id]);

        if (!$page = Page::firstWhere('url_id', $id)) {
            return redirect()->route('page.index');
        }

        if ($request->newPassword != '') $page->password = Hash::make($request->newPassword);
        elseif ($request->newQuestion != '') $page->question = $request->newQuestion;

        $page->save();

        return redirect()->route('page.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!AuthController::checkUserLoggedin($id)) return redirect()->route('page.show', [$id]);

        if ($page = Page::firstWhere('url_id', $id)) {
            $page->words()->delete();
            $page->delete();
        }

        return redirect()->route('page.index');
    }
}
