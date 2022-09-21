<?php

namespace OurNew\BotCore\Concerns;

use OurNew\BotCore\Support\StatisticsBuilder;

interface StatsImplementation
{
    public function handle(): StatisticsBuilder;
}
