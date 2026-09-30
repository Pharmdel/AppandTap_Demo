<?php

use Illuminate\Support\Facades\Route;

// Preview shell: Web shows the pharmacy / Group Owner portal, App shows the
// patient app in a phone frame — the same format as the Pharmdel preview.
Route::view('/', 'welcome')->name('preview');

require __DIR__.'/demo.php';
require __DIR__.'/pharmacy-portal.php';
