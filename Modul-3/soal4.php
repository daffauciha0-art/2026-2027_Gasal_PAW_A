<?php
// soal 4 4.1
echo "======================================= soal 4 4.1 ======================================= <br>" ;
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");


$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$output = array();
foreach($height as $k => $v) { $output[] = "\"$k\"=>\"$v\""; }
echo "height = (" . implode(", ", $output) . ")<br>";
echo "<br>";

// menggunakan perulangan foreach untuk menampilkan data data
foreach($height as $name => $cm) {
    echo "$name is $cm cm tall.<br>";
}

// soal 4 4.2
echo "======================================= soal 4 4.2 ======================================= <br>" ;
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

$output = array();
foreach($weight as $k => $v) { $output[] = "\"$k\"=>\"$v\""; }
echo "weight = (" . implode(", ", $output) . ")<br>";

// mengonversi kunci dan nilai ke numerik
$keys = array_keys($weight);
$values = array_values($weight);
$length = count($weight);

for ($i = 0; $i < $length; $i++) {
    echo $keys[$i] . " is " . $values[$i] . " kg.<br>";
}
?>
