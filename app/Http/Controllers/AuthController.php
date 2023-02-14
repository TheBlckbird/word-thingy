<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Authenticate users for managing their word page
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // return $request;
        $page = Page::firstWhere('url_id', $request->pageId);

        if (Hash::check($request->password, $page->password)) {
            session(['pageLoggedIn' => $request->pageId]);
        }

        return redirect()->route('page.show', [$page->url_id]);
    }

    /**
     * logout of the page
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function logout($id) {
        if (!$page = Page::firstWhere('url_id', $id)) {
            return redirect()->route('page.index');
        }

        session(['pageLoggedIn' => null]);

        return redirect()->route('page.show', [$id]);
    }

    /**
     * check if the user is logged in
     * 
     * @param string $pageId
     * @return bool
     */
    public static function checkUserLoggedin(string $pageId): bool {
        return session('pageLoggedIn') === $pageId ? true : false;
    }
}
