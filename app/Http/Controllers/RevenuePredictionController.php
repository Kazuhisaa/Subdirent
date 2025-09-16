<?php

namespace App\Http\Controllers;
use App\Services\RevenuePredictionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RevenuePrediction;


class RevenuePredictionController extends Controller
{
    
    private $service;

   public function __construct(RevenuePredictionService $service) {
        $this->service = $service;
    }
    
    /**
     * @OA\Get(
     *     path="/api/revenue/predictionMonth",
     *     summary="Get revenue prediction monthly",
     *     tags={"Revenue"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function showRevenuePredictionMonthly()
    {
        // Return response
        return response()->json(
         $this->service->predictMonthly());
    }

     /**
     * @OA\Get(
     *     path="/api/revenue/predictionQuarter",
     *     summary="Get revenue prediction Quarterly",
     *     tags={"Revenue"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function showRevenuePredictionQuarterly(){
      
        return response()->json($this->service->predictQuarterly());
    }


     /**
     * @OA\Get(
     *     path="/api/revenue/predictionAnnual",
     *     summary="Get revenue prediction Annualy",
     *     tags={"Revenue"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function showRevenuePredictionAnnualy(){
        return response()->json($this->service->predictAnnual());
    }
}
