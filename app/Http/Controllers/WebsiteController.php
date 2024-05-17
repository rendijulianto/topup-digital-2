<?php

namespace App\Http\Controllers;

use App\Models\{LogAktivitas};
use Illuminate\Http\Request;


class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('web.pages.website.index');
    }

}