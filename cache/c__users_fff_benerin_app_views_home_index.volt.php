<html>
    <head>
       <title>Tutorial Login</title>
    </head>
    <body>
 <?php
   if ($this->session->has("auth")) {
   $auth = $this->session->get("auth");
   $username=$auth['username'];
   echo "Hallo selamat datang $username ";
   ?>
   <br> <a href="<?= $this->url->get('login/logout') ?>">Logout</a>
   <?php
   }
 ?>
    </body>
</html>