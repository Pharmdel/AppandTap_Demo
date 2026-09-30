<?php

namespace App\Http\Controllers;

use App\Support\ScreenRegistry;
use Illuminate\View\View;

class WebPageController extends Controller
{
    public function show(string $id): View
    {
        $row = ScreenRegistry::get($id);

        return view($row['web']['view'], [
            'screen' => $id,
            'title' => $row['title'],
        ]);
    }
}
