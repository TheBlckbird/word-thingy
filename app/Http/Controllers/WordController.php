<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Word;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery\Generator\StringManipulation\Pass\Pass;

class WordController extends Controller
{
    /**
     * Add a new word to a page
     * 
     * @param string $pageId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function newWord($pageId, Request $request)
    {
        $newWord = trim($request->newWord);

        $alreadyTypedWords = collect(session('words.' . $pageId));

        $page = Page::firstWhere('url_id', $pageId);

        if ($newWord == '' || strlen($newWord) > 20 || in_array($newWord, $page->banned_words)) return redirect()->route('page.show', [$pageId]);

        $word = Word::where('word', $newWord)->where('page_id', $page->id)->first();

        if ($alreadyTypedWords->contains($newWord)) {
            return redirect()->route('page.show', [$pageId]);
        }

        if ($word) {
            $word->count += 1;
            $word->save();

        } else {

            try {
                Word::create(['word' => $newWord, 'count' => 1, 'page_id' => $page->id]);
            } catch (Exception $e) {
                return redirect()->route('page.index');
            }
            
        }

        $alreadyTypedWords->push($newWord);
        session()->put('words.' . $pageId, $alreadyTypedWords->toArray());

        return redirect()->route('page.show', [$pageId]);
    }

    /**
     * Function for banning words on certain pages
     * 
     * @param string $pageId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function banWord($pageId, Request $request) {
        if (!AuthController::checkUserLoggedin($pageId)) return redirect()->route('page.show', [$pageId]);

        $wordToBan = trim($request->word_to_ban);
        if ($wordToBan == '' || strlen($wordToBan) > 20) return redirect()->route('page.show', [$pageId]);

        $page = Page::firstWhere('url_id', $pageId);
        if (!$page) {
            return redirect()->route('page.index');
        }

        if($page->words->firstWhere('word', $wordToBan)) $page->words->firstWhere('word', $wordToBan)->banned = true;
        $page->push();

        $page->banned_words = array_merge($page->banned_words, [$wordToBan]);
        $page->save();

        return redirect()->route('page.show', [$pageId]);
    }

    /**
     * Function for unbanning words on certain pages
     * 
     * @param string $pageId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function unbanWord($pageId, Request $request) {
        if (!AuthController::checkUserLoggedin($pageId)) return redirect()->route('page.show', [$pageId]);

        $wordToUnban = trim($request->word_to_unban);
        if ($wordToUnban == '' || strlen($wordToUnban) > 20) return redirect()->route('page.show', [$pageId]);

        $page = Page::firstWhere('url_id', $pageId);
        if (!$page) {
            return redirect()->route('page.index');
        }

        // if (!in_array($wordToUnban, $page->banned_words)) {
        //     return redirect()->route('page.show', [$pageId]);
        // }
        $index = array_search($wordToUnban, $page->banned_words);
        if (!$index) {
            return redirect()->route('page.index');
        }

        if ($page->words->firstWhere('word', $wordToUnban)) $page->words->firstWhere('word', $wordToUnban)->banned = false;
        $page->push();

        $newBannedWords = $page->banned_words;
        array_splice($newBannedWords, $index);
        $page->banned_words = $newBannedWords;
        $page->save();

        return redirect()->route('page.show', [$pageId]);
    }
}
