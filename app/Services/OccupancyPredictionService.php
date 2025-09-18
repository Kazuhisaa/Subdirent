<?php
namespace App\Services;

use App\Models\OccupancyPrediction;
use Phpml\Regression\LeastSquares;
use Phpml\Metric\Regression as RegressionMetric;
use Carbon\Carbon;
class OccupancyPredictionService{

   public function predictMonthly(){
       $occupancyDataset = OccupancyPrediction::all()->toarray();
         $targets = [];
        $features = [];
        $length = count($occupancyDataset);

        for($i = 1; $i <$length; $i++){
            $prev = $occupancyDataset[$i - 1];
            $curr = $occupancyDataset[$i];

            $features[] = [$curr['Year'],$curr['Month'],$prev['Occupancy rate']];
            $targets[] = $curr['Occupancy rate'];
        }
     
        $regression = new LeastSquares();
        $regression->train($features,$targets);
        $last = end($occupancyDataset);
        $futureYear = $occupancyDataset[$length-1]['Year'] + 1;
        $futureMonth = $occupancyDataset[$length-1]['Month'] + 1;

        
        $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last['Occupancy rate']
        ]);

        
        //  $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        // $r2 = RegressionMetric::r2Score($targets, $predictedAll);

            $lastDate = Carbon::parse($last['Date']);
            $nextDate = $lastDate->copy()->addMonth()->format('Y-m-d');
         return [
                  'prediction' => number_format($predicted,2) ,
                 'Date' => $nextDate
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
                $curr['Year'], $curr['Month'],
                $occupancyDataset[$i-1]['Occupancy rate'],
                $occupancyDataset[$i-2]['Occupancy rate'],
                $occupancyDataset[$i-3]['Occupancy rate']
            ];
            $targets[] = $curr['Occupancy rate'];
        }
   $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($occupancyDataset, -3);
        $futureYear = $occupancyDataset[$length-1]['Year'] + 3;
        $futureMonth = $occupancyDataset[$length-1]['Month'] + 3;

        $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last[2]['Occupancy rate'],
            $last[1]['Occupancy rate'],
            $last[0]['Occupancy rate']
        ]);


        //  $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        // $r2 = RegressionMetric::r2Score($targets, $predictedAll);

            $lastDate = Carbon::parse($last[2]['Date']);
            $nextDate = $lastDate->copy()->addMonth(3)->format('Y-m-d');
         return [
                  'prediction' => number_format($predicted,2) ,
                 'Date' => $nextDate
         ];
   }
  
   public function predictAnnually(){
        $occupancyDataset = OccupancyPrediction::all()->toarray();
         $targets = [];
        $features = [];
        $length = count($occupancyDataset);
        
        for ($i = 12; $i < $length; $i++) {
            $curr = $occupancyDataset[$i];
            $feature = [$curr['Year'], $curr['Month']];
            for ($j = 1; $j <= 12; $j++) {
                $feature[] = $occupancyDataset[$i - $j]['Occupancy rate'];
            }
            $features[] = $feature;
            $targets[] = $curr['Occupancy rate'];
        }

             $regression = new LeastSquares();
        $regression->train($features, $targets);
        
        $last = array_slice($occupancyDataset, -12);
        $futureYear = end($occupancyDataset)['Year'] + 12;
        $futureMonth = end($occupancyDataset)['Month'] + 12;

          $futureFeature = [$futureYear, $futureMonth];
        for ($i = 11; $i >= 0; $i--) {
            $futureFeature[] = $last[$i]['Occupancy rate'];
        }

        $predicted= $regression->predict($futureFeature);


            $lastDate = Carbon::parse($last[11]['Date']);
            $nextDate = $lastDate->copy()->addMonth(12)->format('Y-m-d');
         return [
                  'prediction' => number_format($predicted,2) ,
                  'Date' => $nextDate
         ];
   }     


}