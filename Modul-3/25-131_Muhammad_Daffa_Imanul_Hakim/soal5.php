<?php
// data awal array multidimensi
$students = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

// fungsi bantu untuk mencetak format array sesuai gambar
function printStudentsArray($arr) {
    echo "students = (<br>";
    $total = count($arr);
    $i = 0;
    foreach ($arr as $row) {
        $i++;
        echo '(&quot;' . implode('&quot;, &quot;', $row) . '&quot;)';
        if ($i < $total) {
            echo ',';
        }
        echo '<br>';
    }
    echo ")<br><br>";
}

// menampilkan data awal
echo "Data awal:<br>";
printStudentsArray($students);

// menambahkan 5 data baru
$students[] = array("Daniel", "220404", "0812345611");
$students[] = array("Elena", "220405", "0812345622");
$students[] = array("Fiona", "220406", "0812345633");
$students[] = array("Gabe", "220407", "0812345644");
$students[] = array("Hannah", "220408", "0812345655");

// menampilkan gata stelah ditambah
echo "Data setelah ditambah 5 data lain:<br>";
printStudentsArray($students);

// menampilkan dalam bentuk tabel HTML
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Name</th><th>NIM</th><th>Mobile</th></tr>";

foreach ($students as $row) {
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>