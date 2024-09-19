<?php
   

    for ($i = 0; $i < 100; $i++) {
        $randomNumbers[] = rand(1, 10);
    }
    echo "Random Number: <br>";
    echo "<pre>";
    print_r($randomNumbers);
    echo "</pre>";


    for ($i=0 ; $i<100; $i++) {
        if ($randomNumbers[$i] % 2 == 0) {
            $evenNumbers[] = $randomNumbers[$i];  
            unset($randomNumbers[$i]);
        } else {
            $oddNumbers[] = $randomNumbers[$i];
            unset($randomNumbers[$i]);   
        }
    }
  
    echo "Odd Numbers: <br>";
    echo "<pre>";
    print_r($oddNumbers);
    echo "Sum of odd Number: ";
    print (array_sum($oddNumbers));
    echo "</pre>";

    echo "Even Numbers: <br>";
    echo "<pre>";
    print_r($evenNumbers);
    echo "Sum of Even Number: ";
    print (array_sum($evenNumbers));
    echo "</pre>";

   

    echo "Random Number after removing: <br>";
    echo "<pre>";
    print_r($randomNumbers);
    echo "</pre>";
    
?>
