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
    public function getRevenuePrediction(Request $request)
    {
        // Kunin lahat ng revenue prediction records mula sa model
        $revenueDataset = RevenuePrediction::all()->toArray();

      

        // Prepare features and targets with Lag1
        $features = [];
        $targets = [];

        for ($i = 1; $i < count($revenueDataset); $i++) {
            $prev = $revenueDataset[$i - 1];
            $curr = $revenueDataset[$i];

            // Feature: [Year, Month, Previous Month Revenue]
            $features[] = [
                $curr['Year'],
                $curr['Month'],
                $prev['Historical Revenue']
            ];

            // Target: current month revenue
            $targets[] = $curr['Historical Revenue'];
        }

        // Train regression model
        $regression = new LeastSquares();
        $regression->train($features, $targets);

        // Predict next month (example: Year=62, Month=10)
        $last = end($revenueDataset);
        $predicted = $regression->predict([
            62, // Year
            10, // Month
            $last['Historical Revenue'] // previous month revenue (Lag1)
        ]);

        // Predict for all training samples to calculate R²
        $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        $r2 = RegressionMetric::r2Score($targets, $predictedAll);

        // Return response
        return response()->json([
            'Predicted Month' => 'October 2025 [62, 10]',
            'predicted_revenue' => '₱' . number_format($predicted, 2),
            'r2_score' => round($r2, 2)
        ]);
    }
}
