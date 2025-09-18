<?php
namespace App\Services;

use App\Models\OccupancyPrediction;
use Phpml\Regression\LeastSquares;
use Phpml\Metric\Regression as RegressionMetric;
use Carbon\Carbon;
class OccupancyPredictionService{

   public function predictmonthly(){
       $occupancyDataset = OccupancyPrediction::all()->toarray();
         $targets = [];
        $features = [];
        $length = count($occupancyDataset);

        for($i = 1; $i <$length; $i++){
            $prev = $occupancyDataset[$i - 1];
            $curr = $occupancyDataset[$i];

            $features[] = [$curr['year'],$curr['month'],$prev['occupancy_rate']];
            $targets[] = $curr['occupancy_rate'];
        }
     
        $regression = new LeastSquares();
        $regression->train($features,$targets);
        $last = end($occupancyDataset);
        $futureyear = $occupancyDataset[$length-1]['year'] + 1;
        $futuremonth = $occupancyDataset[$length-1]['month'] + 1;

        
        $predicted = $regression->predict([
            $futureyear, $futuremonth,
            $last['occupancy_rate']
        ]);

        
        //  $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        // $r2 = RegressionMetric::r2Score($targets, $predictedAll);

            $lastdate = Carbon::parse($last['date']);
            $nextdate = $lastdate->copy()->addmonth()->format('Y-m-d');
         return [
                  'prediction' => number_format($predicted,2) ,
                 'date' => $nextdate
         ];
   }
  

   public function predictQuarterly(){
        $occupancyDataset = OccupancyPrediction::all()->toarray();
         $targets = [];
        $features = [];
        $length = count($occupancyDataset);

        
        for ($i = 3; $i < $length; $i++) {
            $curr = $occupancyDataset[$i];
            $features[] = [
                $curr['year'], $curr['month'],
                $occupancyDataset[$i-1]['occupancy_rate'],
                $occupancyDataset[$i-2]['occupancy_rate'],
                $occupancyDataset[$i-3]['occupancy_rate']
            ];
            $targets[] = $curr['occupancy_rate'];
        }
   $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($occupancyDataset, -3);
        $futureyear = $occupancyDataset[$length-1]['year'] + 3;
        $futuremonth = $occupancyDataset[$length-1]['month'] + 3;

        $predicted = $regression->predict([
            $futureyear, $futuremonth,
            $last[2]['occupancy_rate'],
            $last[1]['occupancy_rate'],
            $last[0]['occupancy_rate']
        ]);


        //  $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        // $r2 = RegressionMetric::r2Score($targets, $predictedAll);

            $lastdate = Carbon::parse($last[2]['date']);
            $nextdate = $lastdate->copy()->addmonth(3)->format('Y-m-d');
         return [
                  'prediction' => number_format($predicted,2) ,
                 'date' => $nextdate
         ];
   }
  
   public function predictAnnually(){
        $occupancyDataset = OccupancyPrediction::all()->toarray();
         $targets = [];
        $features = [];
        $length = count($occupancyDataset);
        
        for ($i = 12; $i < $length; $i++) {
            $curr = $occupancyDataset[$i];
            $feature = [$curr['year'], $curr['month']];
            for ($j = 1; $j <= 12; $j++) {
                $feature[] = $occupancyDataset[$i - $j]['occupancy_rate'];
            }
            $features[] = $feature;
            $targets[] = $curr['occupancy_rate'];
        }

             $regression = new LeastSquares();
        $regression->train($features, $targets);
        
        $last = array_slice($occupancyDataset, -12);
        $futureyear = end($occupancyDataset)['year'] + 12;
        $futuremonth = end($occupancyDataset)['month'] + 12;

          $futureFeature = [$futureyear, $futuremonth];
        for ($i = 11; $i >= 0; $i--) {
            $futureFeature[] = $last[$i]['occupancy_rate'];
        }

        $predicted= $regression->predict($futureFeature);


            $lastdate = Carbon::parse($last[11]['date']);
            $nextdate = $lastdate->copy()->addmonth(12)->format('Y-m-d');
         return [
                  'prediction' => number_format($predicted,2) ,
                  'date' => $nextdate
         ];
   }     


}