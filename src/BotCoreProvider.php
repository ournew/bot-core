<?php

namespace OurNew\BotCore;

use Illuminate\Contracts\Foundation\Application;
use OurNew\BotCore\Telegram\Commands\PrivacyCommand;
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
                '2022_01_01_000000_create_chats_table'
            ])
            ->runsMigrations();
    }
    
    public function packageRegistered(): void
    {
        $this->app->extend(Nutgram::class, function (Nutgram $bot, Application $app) {
            
            //Privacy command
            if (config('bot-core.commands.privacy.enabled')) {
                $bot
                    ->onCommand(config('bot-core.commands.privacy.name'), PrivacyCommand::class)
                    ->description(config('bot-core.commands.privacy.description'));
            }
            
            return $bot;
        });
    }
}