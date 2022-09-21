<?php

namespace OurNew\BotCore\Console;

use Illuminate\Console\Command;
use OurNew\BotCore\Concerns\StatsImplementation;

class UpdateBotStatsCommand extends Command
{
    protected $signature = 'stats:update';

    protected $description = 'Update bot statistics cache';

    public function handle(StatsImplementation $statsImplementation): int
    {
        $this->warn('Updating bot stats...');

        $statsImplementation->handle()->build();

        $this->info('Bot stats updated successfully.');

        return 0;
    }
}
