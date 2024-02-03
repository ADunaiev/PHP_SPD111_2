<?php 

//echo '<pre>'; // замість <?= --- виведення відповіді
//print_r( $_SERVER ); // друк масиву

$uri = $_SERVER['REQUEST_URI'];
// якщо у запиті є гет-параметри (знак ?), то прибираємо цю частину
$pos = strpos($uri, '?');
if( $pos > 0) {
    $uri = substr($uri, 0, $pos);
}
$routes = [
    '/basics' => 'basics.php',
    '/layout' => 'layout.php',
    '/' => 'index.php',
];

if(isset($routes[$uri])) { // у маршрутах є відповідний запис
    $page_body = $routes[$uri];
    include '_layout.php';
}
else {
    echo "$uri not found";
}
echo $uri;
