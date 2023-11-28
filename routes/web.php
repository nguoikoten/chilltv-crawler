<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'Chilltv\Crawler\ChilltvCrawler\Controllers',
], function () {
    Route::get('/plugin/chilltv-crawler', 'CrawlController@showCrawlPage');
    Route::get('/plugin/chilltv-crawler/options', 'CrawlerSettingController@editOptions');
    Route::put('/plugin/chilltv-crawler/options', 'CrawlerSettingController@updateOptions');
    Route::get('/plugin/chilltv-crawler/fetch', 'CrawlController@fetch');
    Route::post('/plugin/chilltv-crawler/crawl', 'CrawlController@crawl');
    Route::post('/plugin/chilltv-crawler/get-movies', 'CrawlController@getMoviesFromParams');
});
