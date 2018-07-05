<?php
  if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']))
  {
    if($_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code'])
    {
      $hash = $_COOKIE['hash'];
      $code = $_COOKIE['cookie'];
      $dbc = mysqli_connect('localhost', 'algorithms', 'nexttome', 'algoritm');

      if (!$dbc) {
        exit("Error");
      }
      $q = "SELECT * FROM `users` WHERE hash = '$hash'";
      $q = mysqli_query($dbc, $q);

      if(mysqli_num_rows($q) > 0)
      {
        $d = mysqli_fetch_assoc($q);

        if(substr(sha1(sha1($d['email'])), 0, -6) == $code)
        {
          $_SESSION['id'] = $d['user_id'];
          $_SESSION['name'] = $d['first_name'];
          $_SESSION['surname'] = $d['last_name'];
          $_SESSION['username'] = $d['login'];
          $_SESSION['hash'] = $hash;
          $_SESSION['email'] = $d['email'];
        }
      }
      else 
      {
        session_unset();
        session_destroy();
        setcookie('cookie', '', time() - 3600, '/');
        setcookie('hash', '', time() - 3600, '/');
        setcookie('PHPSESSID','', time() - 3600, '/');
      }
      mysqli_close($dbc);
    }
  }
  else {
    if(isset($_COOKIE['cookie']))
    {
      unset($_COOKIE['cookie']);
      setcookie('cookie', '', time() - 3600, '/');
    }
    else if(isset($_COOKIE['hash'])){
      unset($_COOKIE['hash']);
      setcookie('hash', '', time() - 3600, '/');
    }
  }

?>
    <!DOCTYPE html>
    <html lang="ru">

    <head>
        <title>TheCode</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/resource/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/resource/css/style.css">
        <link rel="stylesheet" type="text/css" href="/resource/css/fontawesome.css">
        <link rel="stylesheet" type="text/css" href="/resource/css/fa-solid.css">
        <script src="/resource/js/js.js"></script>

    <?php 
      if($page == 'ordvac' && $module == 'order')
      {
    ?>
        <style type="text/css" media="all">
          @import "/resource/css/widgEditor.css";
        </style>
        <script type="text/javascript" src="/resource/js/widgEditor.js"></script>
    <?php 
      }
    ?>
        <?php 
    if($page == 'ask' || $page == 'question' && is_numeric($module))
    {
  ?>
            <link media="all" rel="stylesheet" type="text/css" href="/resource/editor/site/assets/styles/simditor.css" />
            <!-- <link media="all" rel="stylesheet" type="text/css" href="/resource/editor/site/assets/styles/app.css" /> -->
            <script src="/resource/editor/site/assets/scripts/mobilecheck.js"></script>
            <script>
                if (mobilecheck()) {
                    $('<link/>', {
                        media: 'all',
                        rel: 'stylesheet',
                        type: 'text/css',
                        href: '/resource/editor/site/assets/styles/mobile.css'
                    }).appendTo('head')
                }
            </script>
            <?php } ?>
    </head>

    <body>
        <nav class="navbar navhead navbar-expand-lg navbar-dark bg-dark fixed-top">
          <div class="container">
            <a class="navbar-brand" href="http://thecode.uz"><img src="/resource/img/thecode.png" height="20px;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarText">
              <ul class="navbar-nav mr-auto">
                <li class = "nav-item"><a class = "nav-link" href="/question">Вопросы</a></li>
                <li class = "nav-item"><a class = "nav-link" href="/ordvac">Заказы</a></li>
                <?php if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) &&
                  $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code']) { ?>
                    <li class = "nav-item"><a class = "nav-link" href="/profile">Профиль</a></li>

                    <?php } ?>
              </ul>
              <form class="form-inline my-2 my-lg-0 mx-auto" action = '/search' method="get">
                <input class="form-control mr-sm-2" type="search" name = "qu" placeholder="Search" aria-label="Search">
              </form>
              <?php if(!isset($_COOKIE['hash']) || !isset($_COOKIE['cookie']) ||
                          $_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code']) { ?>
              <div class="navbar-text">
                  <a class="btn btn-info" href="/login">Войти</a>
                  <a class="btn btn-success" href="/registration">Регистрация</a>
              </div>
              <?php } else if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) &&
                $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code']) { ?>
                  <div class="navbar-text">
                      <a class="btn btn-secondary" href="/ask">Задать вопрос</a>
                  </div>
                  <?php } ?>
            </div>
          </div>

        </nav>
        <div class="all">