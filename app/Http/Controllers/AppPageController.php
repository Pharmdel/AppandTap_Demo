<?php

namespace App\Http\Controllers;

use App\Support\ScreenRegistry;
use Illuminate\View\View;

class AppPageController extends Controller
{
    public function show(string $id): View
    {
        $row = ScreenRegistry::get($id);
        $app = $row['app'];
        // Screens that are a state of another view (e.g. the Consultations tab).
        request()->mergeIfMissing($app['query'] ?? []);

        return view($app['view'], [
            'screen' => $id,
            'title' => $row['title'],
            // The real screen's own AppBar title, where it differs from ours.
            'appTitle' => $id === 'book-appointment' && request()->filled('reschedule')
                ? 'Reschedule Appointment'
                : ($app['appTitle'] ?? $row['title']),
            'appbar' => $app['appbar'] ?? 'appbar',
            'bottomNav' => $app['bottomNav'] ?? 'none',
        ]);
    }
}
