<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class NumberFactController extends Controller
{
    private function isPrime($num)
    {
        if($num <=1){
            return false;
        }
        for ($i = 2; $i <= sqrt($num); $i++) {
            if ($num % $i === 0) return false;
        }
        return true;
    }
    private function isPerfect($num)
    {
        $sum = 0;
        for ($i = 1; $i < $num; $i++) {
            if ($num % $i === 0) $sum += $i;
        }
        return $sum === $num;
    }
    private function isArmstrong($num)
    {
        $originalNum = $num;
        $num = abs($num);
        $digits = str_split($num);
        $numDigits = count($digits);
        $sum = 0;
        foreach ($digits as $digit) {
            $sum += pow($digit, $numDigits);
        }
        return $sum === (int) $originalNum;
    }
    private function digitSum($num)
    {
        return array_sum(str_split($num));
    }
    public function numberFact(Request $request)
    {
        $num = $request->query('number');
        if ($num === null || trim($num) === '') {
            return response()->json([
                'number' => '',  
                'error' => true,
            ], 400);
        }
        if (!is_numeric($num)) {
            return response()->json([
                'number' => $num,
                'error' => true,
            ], 400);
        }
        $originalNum = $num ?? "";
        try {
            $num = (int) $num; 
            $prime = $this->isPrime($num);
            $perfect = $this->isPerfect($num);
            $armstrong = $this->isArmstrong(abs($num));  
            $digit_sum = $this->digitSum($num);
            $properties = [];
            if ($armstrong) $properties[] = 'armstrong';
            if ($num % 2 !== 0) {
                $properties[] = 'odd';
            } else {
                $properties[] = 'even';
            }    
            $response = Http::timeout(3)->get("http://numbersapi.com/{$num}?json");
            $funFact = $response->successful() ? $response->json()['text'] : '';
            return response()->json([
                'number' => $originalNum,
                'is_prime' => $prime,
                'is_perfect' => $perfect,
                'properties' => $properties,
                'digit_sum' => $digit_sum,
                'fun_fact' => $funFact,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'number' => $originalNum,
                'error' => true,
            ], 400);
        }
    }
}
