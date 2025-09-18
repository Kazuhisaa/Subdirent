<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OccupancyPrediction;
use App\Http\Controllers\Controller;
use App\Services\OccupancyPredictionService;
class OccupancyPredictionController extends Controller
{
    //

        
    private $service;

   public function __construct(OccupancyPredictionService $service) {
        $this->service = $service;
    }
    

    public function showOccupancyByMonth(){
        
        return response()->json($this->service->predictMonthly());
    }
   

    public function showOccupancyByQuarter(){
         return response()->json($this->service->predictQuarterly());
    }

    public function showOccupancyByAnnual(){
        return response()->json($this->service->predictAnnually());
    }


    
    
}
