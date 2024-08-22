<?php
$entered_password = 'lann-sa'; // The password entered by the user
$stored_hash = '$2y$10$0M.YewDFo.mo0ZShHNJOROVIsn3KprZMxzr6xLwMXybnhSI1fuXzG'; // Replace with the actual hash from your database

if (password_verify($entered_password, $stored_hash)) {
    echo "Password matches!";
} else {
    echo "Password does not match!";
}
?>
<?php
$new_hashed_password = password_hash('lann-sa', PASSWORD_DEFAULT);
echo $new_hashed_password;
?>
