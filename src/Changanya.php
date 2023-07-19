<?php
namespace Emleons\Games;
use RandomLib\Factory;
use Gambling\Tech\Random;
use Gambling\Tech\FisherYatesShuffle;
class Changanya{
    private $gumbling;
   
    public function __construct()
    {
        $this->gumbling = new Random;
        
    }
	
	function changa(){
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        $generatedNumber = $generator->generateInt(1, 9);
        return $generatedNumber;
        }

        function dunk($array) {
                $numbersToIgnore = array_unique($array);
                $availableNumbers = array_diff(range(1, 9), $numbersToIgnore);
                
                if (empty($availableNumbers)) {
                    return null; // No available unique numbers
                }
                
                $generatedNumber = $availableNumbers[array_rand($availableNumbers)];
                return $generatedNumber;
        }

        function findMostFrequentNumber($array) {
                $countedValues = array_count_values($array);
                
                arsort($countedValues); // Sort the counts in descending order
                
                $mostFrequentNumber = null;
                $highestCount = 0;
                
                foreach ($countedValues as $number => $count) {
                    if ($count >= $highestCount) {
                        $mostFrequentNumber = $number;
                        $highestCount = $count;
                    } else {
                        break; // Break the loop if count decreases
                    }
                }
                
                return [
                    'number' => $mostFrequentNumber,
                    'count' => $highestCount
                ];
            }
    public function chekecha(){
        return $this->gumbling::getInteger(1,9);
    }

    public function shuffleit($array){
        $shuffled = (new FisherYatesShuffle())($array);
        return $shuffled;
    }
            
}
