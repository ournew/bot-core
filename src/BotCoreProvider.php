<?php

namespace OurNew\BotCore;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OurNew\BotCore\Concerns\StatsImplementation;
use OurNew\BotCore\Console\UpdateBotStatsCommand;
use OurNew\BotCore\Telegram\Handlers\ExceptionsHandler;
use OurNew\BotCore\Telegram\Handlers\UpdateChatStatusHandler;
use SergiX44\Nutgram\Nutgram;

class BotCoreProvider extends ServiceProvider
{
    protected string $packageName = 'bot-core';

    public function boot()
    {
        //load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->packageName);

        //load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->packageName);

        //load commands
        $this->commands([
            UpdateBotStatsCommand::class,
        ]);

        //publish config
        $this->publishes([
            __DIR__.'/../config/bot-core.php' => config_path('bot-core.php'),
        ], "{$this->packageName}-config");

        //publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/'.$this->packageName),
        ], "{$this->packageName}-views");

        //publish translations
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/'.$this->packageName),
        ], "{$this->packageName}-translations");

        //schedule commands
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            //update the bot statistics
            $schedule
                ->command(UpdateBotStatsCommand::class)
                ->when(config('bot-core.commands.stats.updater.implementation') !== null)
                ->cron(config('bot-core.commands.stats.updater.cron'));
        });
    }

    public function register()
    {
        //load config
        $this->mergeConfigFrom(__DIR__.'/../config/bot-core.php', $this->packageName);

        //extend Nutgram
        $this->app->extend(Nutgram::class, function (Nutgram $bot, Application $app) {
            $this->loadGlobalMiddlewares($bot);
            $this->loadCommands($bot);
            $this->loadHandlers($bot);
            $this->loadExceptionHandlers($bot);

            return $bot;
        });

        //bind stats implementation
        if (config('bot-core.commands.stats.updater.implementation') !== null) {
            $this->app->bind(StatsImplementation::class, config('bot-core.commands.stats.updater.implementation'));
        }
    }

    protected function loadGlobalMiddlewares(Nutgram $bot): void
    {
        $middlewares = config('bot-core.middlewares');
        foreach ($middlewares as $middleware) {
            $bot->middleware($middleware);
        }
    }

    protected function loadCommands(Nutgram $bot): void
    {
        $commands = config('bot-core.commands');
        foreach ($commands as $command) {
            if (!$command['enabled']) {
                continue;
            }

            $bot->onCommand($command['name'], $command['callable'])->description($command['description']);
        }
    }

    protected function loadHandlers(Nutgram $bot): void
    {
        $bot->onMyChatMember(UpdateChatStatusHandler::class);
    }

    protected function loadExceptionHandlers(Nutgram $bot): void
    {
        $exceptionList = config('bot-core.exceptions.throw');
        foreach ($exceptionList as $pattern => $exception) {
            $bot->onApiError($pattern, fn (Nutgram $bot, $e) => throw new $exception($e->getMessage()));
        }

        $bot->onApiError([ExceptionsHandler::class, 'api']);
        $bot->onException([ExceptionsHandler::class, 'global']);
    }


}
