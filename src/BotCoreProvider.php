<?php

namespace OurNew\BotCore;

use Illuminate\Contracts\Foundation\Application;
use OurNew\BotCore\Telegram\Commands\PrivacyCommand;
use OurNew\BotCore\Telegram\Handlers\UpdateChatStatusHandler;
use SergiX44\Nutgram\Nutgram;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BotCoreProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('bot-core')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasMigrations([
                '2022_01_01_000000_create_chats_table',
                '2022_01_02_000000_create_statistics_table',
            ])
            ->runsMigrations();
    }
    
    public function packageRegistered(): void
    {
        $this->app->extend(Nutgram::class, function (Nutgram $bot, Application $app) {
            $this->loadGlobalMiddlewares($bot);
            $this->loadCommands($bot);
            $this->loadHandlers($bot);
            return $bot;
        });
    }
    
    public function loadGlobalMiddlewares(Nutgram $bot): void
    {
        $middlewares = config('bot-core.middlewares');
        foreach ($middlewares as $middleware) {
            $bot->middleware($middleware);
        }
    }
    
    public function loadCommands(Nutgram $bot): void
    {
        //privacy command
        if (config('bot-core.commands.privacy.enabled')) {
            $bot
                ->onCommand(config('bot-core.commands.privacy.name'), PrivacyCommand::class)
                ->description(config('bot-core.commands.privacy.description'));
        }
    }
    
    public function loadHandlers(Nutgram $bot): void
    {
        $bot->onMyChatMember(UpdateChatStatusHandler::class);
    }
    
    
}