<?php

use Encore\Admin\DataDictionary\Http\Controllers\DataDictionaryController;

Route::get('data-dictionary', DataDictionaryController::class.'@index')->name('dd-index');
Route::get('data-dictionary/desc', DataDictionaryController::class.'@desc')->name('dd-desc-table');
Route::get('data-dictionary/export', DataDictionaryController::class.'@export')->name('dd-export-table');