<?php
  //共通関数の読み込み
  require_once("../common/base.php");
  //db_connect
  try{
    $dbh = dbconnect();
    $stmt = $dbh->prepare("SELECT ID,created_at,title,content FROM t_blogs ORDER BY id DESC");
    $stmt->execute();
    //クエリ実行に失敗した場合
    if( $stmt->errorCode() != '00000'){
      throw new Exception("データが取得できませんでした。");
    }
    $all = $stmt->fetchAll();
    //db切断
    $dbh = null;
  }catch(Exception $e){
    echo  $e->getMessage();
    exit;
  }
  //タイトルを表示
  display_heder();
  //ページタイトルを表示
  display_title();
?>
  <body>
  <a href="./add.php">新規登録</a></br></br>
  <?php
   foreach($all as $loop){
     echo '<a>--------------------------------------</a></br>';
     echo '<a href="./view.php?ID=';
     echo $loop['ID'];
     echo '">';
     echo $loop['title'].PHP_EOL;
     echo '</a></br>';
     echo '<a>--------------------------------------</a></br>';
     echo $loop['content'].PHP_EOL;
     echo '</br></br>';
     echo $loop['created_at'].PHP_EOL;
     echo '</br>';
   }
  ?>
  <a>--------------------------------------</a>
  </body>
</html>
