<?php

namespace OurNew\BotCore\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use function OurNew\BotCore\Helpers\message;

class StatisticsBuilder
{
    protected string $cacheKey;
    protected bool $isLocalized;
    protected Collection $data;

    public function __construct(string $cacheKey = 'stats')
    {
        $this->cacheKey = $cacheKey;
        $this->isLocalized = false;
        $this->data = collect([]);
    }

    public static function make(string $cacheKey = 'stats'): self
    {
        return new self($cacheKey);
    }

    public function localized(): self
    {
        $this->isLocalized = true;

        return $this;
    }

    public function addStat(string $key, callable $callable): self
    {
        $this->data->push([
            'type' => 'stat',
            'key' => $key,
            'value' => $callable,
        ]);

        return $this;
    }

    public function addSpace(int $lines = 1): self
    {
        for ($i = 0; $i < $lines; $i++) {
            $this->data->push([
                'type' => 'space',
                'key' => null,
                'value' => null,
            ]);
        }

        return $this;
    }

    public function build(): void
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return $this
                ->data
                ->filter(fn ($x) => $x['type'] === 'stat')
                ->map(fn ($x) => value($x['value']))
                ->put('__last_update', now()->format('Y-m-d H:i:s e'))
                ->toArray();
        });
    }

    public function get(): string
    {
        $data = Cache::get($this->cacheKey);

        if (blank($data)) {
            return message('stats.empty', vendor: 'bot-core');
        }

        $params = [];

        foreach ($this->data as $index => $item) {
            if ($item['type'] === 'stat') {
                $params[] = [
                    'type' => 'stat',
                    'key' => $item['key'],
                    'value' => Arr::get($data, $index),
                ];
                continue;
            }

            if ($item['type'] === 'space') {
                $params[] = [
                    'type' => 'space',
                    'key' => null,
                    'value' => null,
                ];
            }
        }

        return message('stats.full', [
            'data' => $params,
            'isLocalized' => $this->isLocalized,
            'lastUpdate' => Arr::get($data, '__last_update'),
        ], 'bot-core');
    }

    protected function append(): void
    {
        $this->addSpace();
        $this->addStat('bot-core::stats.last_update', fn () => now()->format('Y-m-d H:i:s e'));
    }
}
