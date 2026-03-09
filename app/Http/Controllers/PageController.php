<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function sellProduct()
    {
        return view('pages.sell-product');
    }

    public function articles()
    {
        return view('pages.articles');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function cancelSelling()
    {
        return view('pages.cancel-selling');
    }

    public function sellAtFinFinPhone()
    {
        return view('pages.sell-at-finfinphone');
    }

    public function howToSell()
    {
        return view('pages.how-to-sell');
    }

    public function howToGetPaid()
    {
        return view('pages.how-to-get-paid');
    }
}
