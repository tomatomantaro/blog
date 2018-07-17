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
   //IDが想定通りの設定の場合
   if ($empty_flg == 1 && $numeric_flg == 1 && $overzero_flg == 1 && $int_flg == 1 ) {
     try{
       $dbh = dbconnect();
       $stmt = $dbh->prepare("SELECT created_at,updated_at,title,content FROM t_blogs WHERE id = :id");
       $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
       $stmt->execute();
       //クエリ実行に失敗した場合
       if( $stmt->errorCode() != '00000'){
         throw new Exception("データが取得できませんでした。");
       }
       $data = $stmt->fetch();
       //db切断
       $dbh = null;
     }catch(Exception $e){
       echo  $e->getMessage();
       exit;
     }
  }
  if( empty($stmt) || $empty_flg == 0 || $numeric_flg == 0 || $overzero_flg == 0 || $int_flg == 0 ){
      echo 'コンテンツが存在しません</br>';
  }
  //タイトルを表示
  display_heder();
  //ページタイトルを表示
  display_title();
 ?>
 <body>
   <a href="./edit.php?ID=<?php echo $ID;?>">編集</a></br>
   <a href="./del.php?ID=<?php echo $ID;?>">削除</a></br></br>
   <a>--------------------------------------</a></br>
   <a>
    <?php
      echo $data['title'].PHP_EOL;
    ?>
    </a></br>
    <a>--------------------------------------</a></br>
    <a>
    <?php
      echo $data['content'].PHP_EOL;
    ?>
    </a></br></br>
    &nbsp;&nbsp;&nbsp;
    <a>
    <?php
       echo $data['created_at'].PHP_EOL;
    ?>
    </a>
    &nbsp;
    <a>登録</a></br>
    <?php
        if($data['created_at'] != $data['updated_at']){
          echo '&nbsp;&nbsp;&nbsp;&nbsp;';
          echo $data['updated_at'].PHP_EOL;
          echo '</a>&nbsp;&nbsp;更新</a></br>';
        }
    ?>
    </br><a href="./index.php">戻る</a>
  </body>
</html>
