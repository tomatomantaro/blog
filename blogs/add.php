<?php
  //共通関数の読み込み
  require_once("../common/base.php");
  //タイトル、内容フラグ
  $title_flg = 1;
  $content_flg = 1;
  //POST送信が来た場合の処理
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["title"])) {
      echo "記事タイトルが未入力です</br>";
      $title_flg = 0;
    }
    if (empty($_POST["content"])){
      echo "記事内容が未入力です</br>";
      $content_flg = 0;
    }
    //タイトル、内容が存在している場合の処理
    if ($title_flg == 1 && $content_flg == 1){
      //db_connect
      try{
         $dbh = dbconnect();
         $stmt = $dbh->prepare("SELECT ID FROM t_blogs ORDER BY ID DESC LIMIT 1");
         $stmt->execute();
         //クエリ実行に失敗した場合
         if( $stmt->errorCode() != '00000'){
           throw new Exception("登録に失敗しました");
         }
         $ID = $stmt->fetchColumn();
         $ID = $ID + 1;
         $itmt = $dbh->prepare("INSERT INTO t_blogs (id, created_at, updated_at, title, content, user_id) VALUES ( :id, cast( now() as datetime ) , cast( now() as datetime ) , :title , :content, '0' )");
         $itmt->bindParam(':id', $ID, PDO::PARAM_INT);
         $itmt->bindParam(':title', $_POST["title"], PDO::PARAM_STR);
         $itmt->bindParam(':content', $_POST["content"], PDO::PARAM_INT);
         $itmt->execute();
         //クエリ実行に失敗した場合
         if( $itmt->errorCode() != '00000'){
           throw new Exception("登録に失敗しました");
         }
         //db切断
         $dbh = null;
      }catch(Exception $e){
          echo  $e->getMessage();
          exit;
      }
      header( "Location: ./view.php?ID=$ID" ) ;
  }
}
  //ヘッダ部表示
  display_heder();
  //ページタイトルを表示
  display_title();
?>
  <body>
   <form action="./add.php" method="post">
     <a>記事タイトル</a></br>
     <input type="text" name="title" size="30" maxlength="20"></br>
        <a>記事内容</a></br>
        <textarea name="content" rows="3" cols="30"></textarea><br><br>
        <a href="./index.php">戻る</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit"  value="登録">
    </form>
  </body>
</html>
