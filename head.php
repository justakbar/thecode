<?php
  if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']))
  {
    if($_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code'])
    {
      $hash = $_COOKIE['hash'];
      $code = $_COOKIE['cookie'];

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
          $_SESSION['let'] = "iletugo";
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
        <script src="/resource/js/js.js"></script>
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
        <nav class="navbar navhead navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <i class="glyphicon glyphicon-menu-hamburger" style="color: white;"></i>
                    </button>
                    <a class="navbar-brand brand" href="http://thecode.uz"><img src="/resource/img/thecode.png" height="30" alt="logotip"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="/question">Вопросы</a></li>
                        <?php if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) &&
                          $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code']) { ?>
                            <li><a href="/profile">Профиль</a></li>

                            <?php } ?>
                    </ul>
                    <form action="/search" method="get" class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search" name="qu">
                        </div>
                    </form>
                    <?php if(!isset($_COOKIE['hash']) || !isset($_COOKIE['cookie']) ||
                          $_COOKIE['hash'] != $_SESSION['hash'] || $_COOKIE['cookie'] != $_SESSION['code']) { ?>
                        <div class="nav navbar-nav navbar-right">
                            <a class="btn btn-default margin" href="/login">Войти</a>
                            <a class="btn btn-success margin" href="/registration">Регистрация</a>
                        </div>
                        <?php } else if(isset($_COOKIE['hash']) && isset($_COOKIE['cookie']) &&
                          $_COOKIE['hash'] == $_SESSION['hash'] && $_COOKIE['cookie'] == $_SESSION['code']) { ?>
                            <div class="nav navbar-nav navbar-right">
                                <a class="btn btn-default margin" href="/ask">Задать вопрос</a>
                            </div>
                            <?php } ?>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <div class="all">