
<?php
  //ページタイトルを表示
  function display_title(){
      echo '<h1>title</h1>';
  }
  //タイトルを表示
  function display_heder(){
      echo '
      <!DOCTYPE html>
      <html lang="ja">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script type="text/javascript" src=../js/clic.js></script>
        <title>LAMPブログ</title>
      </head>
      ';
  }
  //DB接続
  function dbconnect(){
    $dsn = 'mysql:dbname=BLOG;host=localhost';
    $user = 'root';
    $password = 'vagrant';
    try{
      $dbh = new PDO($dsn, $user, $password);
      return $dbh;
    } catch (PDOException $e) {
      throw new Exception("DB接続に失敗しました");
    }
  }
?>
