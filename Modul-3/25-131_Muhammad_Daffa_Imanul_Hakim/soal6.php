<?php
// array_push()
$arr1 = array("A");
array_push($arr1, "B");
echo "Array awal: (\"A\")<br>";
echo "Hasil array_push: " . implode(" ", $arr1) . "<br><br>";

// array_merge()
$arr2_1 = array("A", "B");
$arr2_2 = array("C");
$result_merge = array_merge($arr2_1, $arr2_2);
echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")<br>";
echo "Hasil array_merge: " . implode(" ", $result_merge) . "<br><br>";

// array_values()
$arr3 = array("x"=>1, "y"=>2);
$result_values = array_values($arr3);
echo "Array awal: (\"x\"=>1, \"y\"=>2)<br>";
echo "Hasil array_values: " . implode(" ", $result_values) . "<br><br>";

// array_search()
$arr4 = array("A", "B", "C");
$result_search = array_search("B", $arr4);
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>";
echo "Hasil array_search: " . $result_search . "<br><br>";

// 5. array_filter()
$arr5 = array(0, 1, false, 2, "", 3, "array");
$result_filter = array_filter($arr5);
echo "Array awal: (0, 1, false, 2, \"\", 3, \"array\")<br>";
echo "Hasil array_filter: " . implode(" ", $result_filter) . "<br><br>";

// Sorting Terindeks (sort & rsort)
$arr6 = array(3, 1, 2);
$s1 = $arr6; sort($s1);
$s2 = $arr6; rsort($s2);
echo "Array awal: (3, 1, 2)<br>";
echo "Hasil sort: " . implode(" ", $s1) . "<br>";
echo "Hasil rsort: " . implode(" ", $s2) . "<br><br>";

// Sorting Asosiatif (asort, ksort, arsort, krsort)
$assoc = array("Peter"=>35, "Ben"=>37, "Joe"=>43);

$as = $assoc; asort($as);
$ks = $assoc; ksort($ks);
$ars = $assoc; arsort($ars);
$krs = $assoc; krsort($krs);

function printAssoc($arr) {
    $out = array();
    foreach($arr as $k => $v) { $out[] = "$k=>$v"; }
    return implode(", ", $out);
}

echo "Array awal: (\"Peter\"=>35, \"Ben\"=>37, \"Joe\"=>43)<br>";
echo "Hasil asort: " . printAssoc($as) . "<br>";
echo "Hasil ksort: " . printAssoc($ks) . "<br>";
echo "Hasil arsort: " . printAssoc($ars) . "<br>";
echo "Hasil krsort: " . printAssoc($krs) . "<br>";
?>