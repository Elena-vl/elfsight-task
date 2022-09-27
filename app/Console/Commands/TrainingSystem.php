<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Обучения нейронной сети предсказанию тональности английского текста
 *
 * Class TrainingSystem
 *
 * @package App\Console\Commands
 */
class TrainingSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'training-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Neural network training';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $analysis = new \App\Services\SentimentAnalysis();

        $analysis->train();
        $analysis->validate();

        return 0;
    }
}
