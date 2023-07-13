<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class ListarSchedulesCommand extends Command
{
    protected $signature = 'schedule:list';
    protected $description = 'Lista de comandos scheduled.';

    /**
     * @var Schedule
     */
    protected Schedule $schedule;

    /**
     * ScheduleList constructor.
     *
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        parent::__construct();

        $this->schedule = $schedule;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $events = array_map(function ($event) {
            return [
                'cron' => $event->expression,
                'command' => static::fixupCommand($event->command),
            ];
        }, $this->schedule->events());

        $this->table(
            ['Cron', 'Command'],
            $events
        );
    }

    /**
     * If it's an artisan command, strip off the PHP
     *
     * @param $command
     * @return string
     */
    protected static function fixupCommand($command): string
    {
        $parts = explode(' ', $command);
        if (count($parts) > 2 && $parts[1] === "'artisan'") {
            array_shift($parts);
        }

        return implode(' ', $parts);
    }
}
