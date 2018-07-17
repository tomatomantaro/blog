<?php
  //共通関数の読み込み
  require_once("../common/base.php");
  //GET情報取得
  $ID = $_GET['ID'];
  //フラグ設定
  $empty_flg = 1;
  $numeric_flg = 1;
  $overzero_flg = 1;
  $int_flg = 1;
  $title_flg = 1;
  $content_flg = 1;
  if (empty($ID)) {
    echo 'IDが未指定です</br>';
    $empty_flg = 0;
  }
  if (!is_numeric($ID)){
    echo 'IDが数値ではありません</br>';
    $numeric_flg = 0;
  }
  if ($ID<0){
     echo 'IDは０より大きい数値にしてください</br>';
     $overzero_flg = 0;
   }
   if (!filter_var($ID, FILTER_VALIDATE_INT)) {
     echo 'IDが整数ではありません</br>';
     $int_flg = 0;
   }
   if( $empty_flg == 0 || $numeric_flg == 0 || $overzero_flg == 0 || $int_flg == 0 ){
     echo '存在しないIDであるため、記事一覧画面へ飛びます</br>';
     header("refresh:5;url=./index.php");
     exit;
   }
   //IDが想定通りの設定の場合
   if ($empty_flg == 1 && $numeric_flg == 1 && $overzero_flg == 1 && $int_flg == 1 ) {
     echo $_POST["content"];
     echo $_POST["title"];
  //POST送信が来た場合
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
       if (empty($_POST["title"])) {
         echo "記事タイトルが未入力です</br>";
         $title_flg = 0;
       }
       if (empty($_POST["content"])){
         echo "記事内容が未入力です</br>";
         $content_flg = 0;
       }
       if ($title_flg == 1 && $content_flg == 1){
         try{
           echo $_POST["title"];
           echo $_POST["content"];
           $dbh = dbconnect();
           $utmt = $dbh->prepare("UPDATE t_blogs SET title = :title ,content = :content ,updated_at = cast( now() as datetime ) WHERE id = :id");
           $utmt->bindParam(':title', $_POST["title"], PDO::PARAM_STR);
           $utmt->bindParam(':content', $_POST["content"], PDO::PARAM_INT);
           $utmt->bindParam(':id', $ID, PDO::PARAM_INT);
           $utmt->execute();
           //クエリ実行に失敗した場合
           if( $utmt->errorCode() != '00000'){
             throw new Exception("更新に失敗しました。");
           }
           //db切断
           $dbh = null;
           header( "Location: ./view.php?ID=$ID" ) ;
        }catch(Exception $e){
          echo  $e->getMessage();
          exit;
        }
      }
    }else{
        try{
          $dbh = dbconnect();
          $stmt = $dbh->prepare("SELECT created_at,updated_at,title,content FROM t_blogs WHERE id = :id");
          $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
          $stmt->execute();
          $data = $stmt->fetch();
          $dbh = null;
        }catch(Exception $e){
          echo  $e->getMessage();
        }
    }
  }
  //ページタイトルを表示
  display_title();
  //タイトルを表示
  display_heder();
 ?>
 <body>
   <form action="./edit.php?ID=<?php echo $ID;?>" method="post">
   <a>記事タイトル</a></br>
   <input type="text" name="title" size="30" maxlength="20" value=<?php echo $data['title'] ?>></br>
   <a>記事内容</a></br>
   <textarea name="content" rows="3" cols="30"><?php echo $data['content'] ?></textarea><br><br>
   &nbsp;&nbsp;&nbsp;
   <a>
     <?php
       echo $data['created_at'].PHP_EOL;
     ?>
   </a>
   &nbsp;
   <a>登録</a></br>
   <a>
     <?php
        if($data['created_at'] != $data['updated_at']){
          echo '&nbsp;&nbsp;&nbsp;&nbsp;';
          echo $data['updated_at'].PHP_EOL;
          echo '<a>&nbsp;&nbsp;更新</a></br>';
        }
     ?>
   </br><a href="./view.php?ID=<?php echo $ID;?>">戻る</a>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <input type="submit"  value="更新">
 </form>
</body>
</html>
