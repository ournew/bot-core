<?php

namespace OurNew\BotCore;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OurNew\BotCore\Telegram\Commands\PrivacyCommand;
use OurNew\BotCore\Telegram\Handlers\UpdateChatStatusHandler;
use SergiX44\Nutgram\Nutgram;

class BotCoreProvider extends ServiceProvider
{
    protected string $packageName = 'bot-core';
    
    public function boot()
    {
        //load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        //load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', $this->packageName);
        
        //load translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', $this->packageName);
        
        //publish config
        $this->publishes([
            __DIR__ . '/../config/bot-core.php' => config_path('bot-core.php'),
        ], "{$this->packageName}-config");
        
        //publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/' . $this->packageName),
        ], "{$this->packageName}-views");
        
        //publish translations
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/' . $this->packageName),
        ], "{$this->packageName}-translations");
    }
    
    public function register()
    {
        //load config
        $this->mergeConfigFrom(__DIR__ . '/../config/bot-core.php', $this->packageName);
        
        //extend Nutgram
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