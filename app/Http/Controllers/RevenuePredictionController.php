<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RevenuePrediction;

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
        $revenueDataset = json_decode(RevenuePrediction::all(), true);
        $year_and_month[];
        $revenue[];

        foreeach($revenueDataset as $data){
            
        }

        
        return response()->json([
            'prediction' => 'revenue prediction data'
        ]);
    }
}
