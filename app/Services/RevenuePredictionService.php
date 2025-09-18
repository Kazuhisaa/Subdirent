<?php
namespace App\Services;

use App\Models\RevenuePrediction;
use Phpml\Regression\LeastSquares;
use Carbon\Carbon;

class RevenuePredictionService
{
    public function predictMonthly()
    {
        $dataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($dataset);

        for ($i = 1; $i < $length; $i++) {
            $prev = $dataset[$i - 1];
            $curr = $dataset[$i];

            $features[] = [$curr['Year'], $curr['Month'], $prev['Historical Revenue']];
            $targets[] = $curr['Historical Revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = end($dataset);
        $futureYear = $last['Year'] + 1;
        $futureMonth = $last['Month'] + 1;

        $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last['Historical Revenue']
        ]);

        $lastDate = Carbon::parse($last['Date']);
        $nextDate = $lastDate->copy()->addMonth()->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'Date' => $nextDate
        ];
    }

    public function predictQuarterly()
    {
        $dataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($dataset);

        for ($i = 3; $i < $length; $i++) {
            $curr = $dataset[$i];
            $features[] = [
                $curr['Year'], $curr['Month'],
                $dataset[$i-1]['Historical Revenue'],
                $dataset[$i-2]['Historical Revenue'],
                $dataset[$i-3]['Historical Revenue']
            ];
            $targets[] = $curr['Historical Revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($dataset, -3);
        $futureYear = end($dataset)['Year'] + 3;
        $futureMonth = end($dataset)['Month'] + 3;

        $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last[2]['Historical Revenue'],
            $last[1]['Historical Revenue'],
            $last[0]['Historical Revenue']
        ]);

        $lastDate = Carbon::parse($last[2]['Date']);
        $nextDate = $lastDate->copy()->addMonths(3)->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'Date' => $nextDate
        ];
    }

    public function predictAnnual()
    {
        $dataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($dataset);

        for ($i = 12; $i < $length; $i++) {
            $curr = $dataset[$i];
            $feature = [$curr['Year'], $curr['Month']];
            for ($j = 1; $j <= 12; $j++) {
                $feature[] = $dataset[$i - $j]['Historical Revenue'];
            }
            $features[] = $feature;
            $targets[] = $curr['Historical Revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($dataset, -12);
        $futureYear = end($dataset)['Year'] + 12;
        $futureMonth = end($dataset)['Month'] + 12;

        $futureFeature = [$futureYear, $futureMonth];
        for ($i = 11; $i >= 0; $i--) {
            $futureFeature[] = $last[$i]['Historical Revenue'];
        }

        $predicted = $regression->predict($futureFeature);

        $lastDate = Carbon::parse($last[11]['Date']);
        $nextDate = $lastDate->copy()->addMonths(12)->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'Date' => $nextDate
        ];
    }
}
