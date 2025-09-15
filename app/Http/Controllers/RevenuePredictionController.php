<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RevenuePrediction;
use Phpml\Regression\LeastSquares;
use Phpml\Metric\Regression as RegressionMetric;

class RevenuePredictionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/revenue/prediction",
     *     summary="Get revenue prediction",
     *     tags={"Revenue"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function getRevenuePredictionMonthly()
    {

        $revenueDataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($revenueDataset);
        for ($i = 1; $i < $length; $i++) {
            $prev = $revenueDataset[$i - 1];
            $curr = $revenueDataset[$i];

            $features[] = [
                $curr['Year'],
                $curr['Month'],
                $prev['Historical Revenue']
            ];

            $targets[] = $curr['Historical Revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = end($revenueDataset);
        $futureyear = $revenueDataset[$length-1]['Year']+1;
        $futuremonth = $revenueDataset[$length-1]['Month'] +1;
        $predicted = $regression->predict([
            $futureyear, // Year
            $futuremonth, // Month
            $last['Historical Revenue'] // previous month revenue (Lag1)
        ]);

        $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        $r2 = RegressionMetric::r2Score($targets, $predictedAll);
          
        // Return response
        return response()->json([
            'predicted_revenue for next month' => '₱' . number_format($predicted, 2),
            'r2_score' => round($r2, 2)
        ]);
    }

    public function getRevenuePredictionQuarterly(){
        $revenueDataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];

        for($i = 3; i <  $length = count($revenueDataset);$i++)
        {
            $curr = $revenueDataset[$i];

            $features = [
                $curr['Year'],
                $curr['Month'],
                $revenueDataset[$i-1]['Historical Revenue'],
                $revenueDataset[$i-2]['Historical Revenue'],
                $revenueDataset[$i-3]['Historical Revenue']
            ];

              $targets = $curr['Historical Revenue'];
        }

        
        $regression = new LeastSquares();
        $regression->train($features, $targets);

    }
}
