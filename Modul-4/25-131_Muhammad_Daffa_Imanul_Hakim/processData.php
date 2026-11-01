<?php
$errors = array();

// cekapakah form telah disubmit
if (isset($_GET['submit'])) {
    // Panggil file validasi
    require 'validate.inc';
    
    //  validasi untuk surname
    validateName($errors, $_GET, 'surname');
    
    // terdapat error
    if ($errors) {
        echo '<h3>Invalid, correct the following errors:</h3>';
        foreac  h ($errors as $field => $error) {
            echo "<p style='color: red;'>$field: $error</p>";
        }
        // menampilkan kembali form (sticky form) menggunakan file terpisah
        include 'form.inc';
    } else {
        // Jika lolos validasi, tampilkan data yang dikirimkan
        echo '<h3>Form submitted successfully! posted data:</h3>';
        foreach ($_GET as $key => $value) {
            if ($key != 'submit') {
                echo "($key) => ($value) <br/>";
            }
        }
    }
} else {
    // jika form baru pertama kali dibuka, tampilkan form kosong dari file terpisah
    include 'form.inc';
}
?>