<?php

namespace Chilltv\Crawler\ChilltvCrawler;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as SP;
use Chilltv\Crawler\ChilltvCrawler\Console\CrawlerScheduleCommand;
use Chilltv\Crawler\ChilltvCrawler\Option;

class ChilltvCrawlerServiceProvider extends SP
{
    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [];
    }

    public function register()
    {

        config(['plugins' => array_merge(config('plugins', []), [
            'chilltvpack/chilltv-crawler' =>
            [
                'name' => 'Chilltv Crawler',
                'package_name' => 'chilltvpack/chilltv-crawler',
                'icon' => 'la la-hand-grab-o',
                'entries' => [
                    ['name' => 'Crawler', 'icon' => 'la la-hand-grab-o', 'url' => backpack_url('/plugin/chilltv-crawler')],
                    ['name' => 'Option', 'icon' => 'la la-cog', 'url' => backpack_url('/plugin/chilltv-crawler/options')],
                ],
            ]
        ])]);

        config(['logging.channels' => array_merge(config('logging.channels', []), [
            'chilltv-crawler' => [
                'driver' => 'daily',
                'path' => storage_path('logs/chilltvpack/chilttv-crawler.log'),
                'level' => env('LOG_LEVEL', 'debug'),
                'days' => 7,
            ],
        ])]);

        config(['chilltv.updaters' => array_merge(config('chilltv.updaters', []), [
            [
                'name' => 'Chilltv Crawler',
                'handler' => 'Chilltv\Crawler\ChilltvCrawler\Crawler'
            ]
        ])]);
    }

    public function boot()
    {
        $this->commands([
            CrawlerScheduleCommand::class,
        ]);

        $this->app->booted(function () {
            $this->loadScheduler();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'chilltv-crawler');
    }

    protected function loadScheduler()
    {
        $schedule = $this->app->make(Schedule::class);
        $schedule->command('chilltv:plugins:chilltv-crawler:schedule')->cron(Option::get('crawler_schedule_cron_config', '*/10 * * * *'))->withoutOverlapping();
    }
}
