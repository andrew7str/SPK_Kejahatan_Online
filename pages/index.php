<?php
// Redirect to main index.php with query parameters
$query = $_SERVER['QUERY_STRING'];
$redirect_url = '../index.php';
if (!empty($query)) {
    $redirect_url .= '?' . $query;
}
header('Location: ' . $redirect_url);
exit();
?>
