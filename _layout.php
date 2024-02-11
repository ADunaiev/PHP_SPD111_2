<!doctype html>
<html>
<head>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<!--Let browser know website is optimized for mobile-->      
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
	<title>PHP-SPD111</title>
    <link rel="stylesheet" href="/css/site.css">
</head>
<body>
	<div class="container">

        <nav>
            <div class="nav-wrapper light-blue">
                <a href="/" class="brand-logo"><img src="/img/PHP_logo.png"/></a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <?php foreach( [
                        'basics' => 'Основи',
                        'layout' => 'Шаблонізація',
                        'api' => 'API', 
                    ] as $href => $name ) : ?>
                    <li <?= $uri==$href ? 'class="active"' : '' ?> ><a href="/<?= $href ?>"><?= $name ?></a></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </nav>
        
        <?php include $page_body ; ?>
    </div>

<!-- Compiled and minified JavaScript -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <script src="/js/site.js"></script>
</body>

    
</html>
