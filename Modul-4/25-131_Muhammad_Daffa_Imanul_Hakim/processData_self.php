<?php
$errors = array();

if (isset($_POST['submit'])) {
    require 'validate.inc';
    
    // Validasi field surname
    validateName($errors, $_POST, 'surname');
    
    if ($errors) {
        echo '<h1>Invalid, correct the following errors:</h1>';
        foreach ($errors as $field => $error) {
            echo "$field: $error<br/>";
        }
        // Tampilkan kembali form beserta data yang sudah diisi sebelumnya
        include 'form.inc';
    } else {
        echo 'Form submitted successfully with no errors';
    }
} else {
    // Jika belum disubmit, tampilkan form kosong
    include 'form.inc';
}
?>  