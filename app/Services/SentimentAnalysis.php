<?php

namespace App\Services;

use Rubix\ML\CrossValidation\Reports\AggregateReport;
use Rubix\ML\CrossValidation\Reports\ConfusionMatrix;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Loggers\Screen;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Tokenizers\NGram;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\ZScaleStandardizer;
use Rubix\ML\Classifiers\MultilayerPerceptron;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\Layers\PReLU;
use Rubix\ML\NeuralNet\Layers\BatchNorm;
use Rubix\ML\NeuralNet\ActivationFunctions\LeakyReLU;
use Rubix\ML\NeuralNet\Optimizers\AdaMax;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Extractors\CSV;

/**
 * Обучения нейронной сети предсказанию тональности английского текста
 *
 * Class SentimentAnalysis
 *
 * @package App\Services
 */
class SentimentAnalysis
{
    /**
     * Обучение
     */
    public function train(): void
    {
        ini_set('memory_limit', '-1');

        $logger = new Screen();

        $logger->info('Loading data into memory');

        $samples = $labels = [];

        foreach (['positive', 'negative'] as $label) {
            foreach (glob("vendor/rubix/sentiment/train/$label/*.txt") as $file)
            {
                $samples[] = [file_get_contents($file)];
                $labels[] = $label;
            }
        }

        $dataset = new Labeled($samples, $labels);

        $estimator = new PersistentModel(
            new Pipeline(
                [
                    new TextNormalizer(),
                    new WordCountVectorizer(
                        1000, 0.00008, 0.4, new NGram(1, 2)
                    ),
                    new TfIdfTransformer(),
                    new ZScaleStandardizer(),
                ], new MultilayerPerceptron([
                    new Dense(100),
                    new Activation(new LeakyReLU()),
                    new Dense(100),
                    new Activation(new LeakyReLU()),
                    new Dense(100, 0.0, false),
                    new BatchNorm(),
                    new Activation(new LeakyReLU()),
                    new Dense(50),
                    new PReLU(),
                    new Dense(50),
                    new PReLU(),
                ], 256, new AdaMax(0.0001))
            ),
            new Filesystem(storage_path('sentiment.rbx'), true)
        );

        $estimator->setLogger($logger);

        $estimator->train($dataset);

        $extractor = new CSV(storage_path('progress.csv'), true);

        $extractor->export($estimator->steps());

        $logger->info('Progress saved to progress.csv');

        $estimator->save();
    }

    /**
     * Перекрестная проверка
     */
    public function validate(): void
    {
        ini_set('memory_limit', '-1');

        $logger = new Screen();

        $logger->info('Loading data into memory');

        $samples = $labels = [];

        foreach (['positive', 'negative'] as $label) {
            foreach (glob("vendor/rubix/sentiment/train/$label/*.txt") as $file)
            {
                $samples[] = [file_get_contents($file)];
                $labels[] = $label;
            }
        }

        $dataset = Labeled::build($samples, $labels)->randomize()->take(10000);

        $estimator = PersistentModel::load(
            new Filesystem(storage_path('sentiment.rbx'))
        );

        $logger->info('Making predictions');

        $predictions = $estimator->predict($dataset);

        $report = new AggregateReport([
            new MulticlassBreakdown(),
            new ConfusionMatrix(),
        ]);

        $results = $report->generate($predictions, $dataset->labels());

        echo $results;

        $results->toJSON()->saveTo(new Filesystem(storage_path('report.json')));

        $logger->info('Report saved to report.json');
    }

    /**
     * Прогнозирование отдельных выборок
     */
    public function predict(string $text): float
    {
        //https://prnt.sc/WonztwVJK1Jm
        ini_set('memory_limit', '-1');

        $estimator = PersistentModel::load(
            new Filesystem(storage_path('sentiment.rbx'))
        );

        $dataset = new Unlabeled([
            [$text],
        ]);

        // Обучаем систему
        $estimator->predict($dataset);

        // Из уже обученной системы можем получить оценку
        $params = $estimator->base()->params();

        $probabilities = $params['estimator']->proba($dataset);
        $probabilities = current($probabilities);
        $predict = max($probabilities);

        return round($predict, 4, PHP_ROUND_HALF_UP);
    }
}