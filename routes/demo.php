<?php

use App\Http\Controllers\AppPageController;
use App\Support\ScreenRegistry;
use Illuminate\Support\Facades\Route;

foreach (ScreenRegistry::all() as $id => $row) {
    // "Web" is the pharmacy / Group Owner portal shown in the preview shell,
    // so the old patient-website URLs send people there. The names stay so
    // the app's Web/App pill keeps resolving.
    Route::redirect("web/{$id}", '/?mode=web')->name("web.{$id}");

    $entry = $row['app'];

    if ($entry['type'] === 'view') {
        Route::get("app/{$id}", fn () => app(AppPageController::class)->show($id))->name("app.{$id}");
    } else {
        Route::get("app/{$id}", fn () => redirect()->route("app.{$entry['target']}", ['from' => $id]))
            ->name("app.{$id}");
    }
}
