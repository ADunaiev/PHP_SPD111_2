<?php
    session_start();
    if(isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $interval = time() - $_SESSION['auth-moment'];
        if($interval > 30) {
            unset($_SESSION['user']);
            unset($_SESSION['auth-moment']);

        }
        else {
            $user = $_SESSION['user'];
            $_SESSION['auth-moment'] = time();
        }
    }
    else {
        $user = null;
    }
?>
<!doctype html>
<html>
<head>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
	
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
                    <!-- Modal Trigger -->
                    <?php if ($user == null) { ?>
                        <li><a class="modal-trigger btn-flat" href="#modal1"><i style="color:white;" class="material-icons">perm_identity</i></a></li>
                    <?php } else {?>
                        <li><a><?= $user['name'] ?></a></li>
                        <li><a><img class="rounded nav-avatar responsive-img" src="avatar/<?= $user['avatar']?>"></a></li>
                    <?php } ?>
                    
                </ul>

            </div>
        </nav>
        <?= var_export($user, true) ?></br> 
        Repeat Auth in <?= 30 - $interval ?> sec
        
        <?php include $page_body ; ?>
</body>

<footer class="page-footer light-blue">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">Footer Content</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2024 Copyright Text
            <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
            </div>
          </div>
</footer>

<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <h5>Вхід у систему</h5>
        <div class="row">
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">email</i>
                        <input id="sign-in-email" name="sign-in-email" type="email" class="validate">
                        <label for="sign-in-email">Email</label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">password</i>
                        <input id="sign-in-password" name="sign-in-password" type="password" class="validate">
                        <label for="sign-in-password">Password</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="row center-align">   
            <div class="col s12">
                <a href="#!" id="auth-button" style="width:100%;" class="modal-close waves-effect blue btn">Sign in</a>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <a href="/signup" style="width:100%;" class="modal-close waves-effect waves-green btn">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<!-- Compiled and minified JavaScript -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <script src="/js/site.js"></script>

   
</html>
