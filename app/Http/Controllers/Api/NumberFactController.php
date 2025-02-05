<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
        $num = $request->input('number');
        if (!is_numeric($num)) {
            return response()->json([
                'number' => $num,
                'error' => true,
            ], 400);
        }
        if (trim($num) === '' || !isset($num)) {
            return response()->json([
                'number' => "",
                'error' => true,
            ], 400);
        }
        $originalNum = $num ?? "";
        try {
            $intNum = (int) $num; 
            $prime = $this->isPrime($intNum);
            $perfect = $this->isPerfect($intNum);
            $armstrong = $this->isArmstrong(abs($intNum));  
            $digit_sum = $this->digitSum($intNum);
            $properties = [];
            if ($armstrong) $properties[] = 'armstrong';
            if ($num % 2 !== 0) {
                $properties[] = 'odd';
            } else {
                $properties[] = 'even';
            }

            $funFact = Cache::remember("fun_fact_{$intNum}", now()->addHours(24), function () use ($intNum) {
                $response = Http::timeout(2)->get("http://numbersapi.com/{$intNum}?json");
                return $response->successful() ? $response->json()['text'] : '';
            });
            // $response = Http::get("http://numbersapi.com/{$num}?json");
            // $funFact = $response->successful() ? $response->json()['text'] : '';
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
