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
  if( $empty_flg == 0 || $numeric_flg == 0 || $overzero_flg == 0 || $int_flg == 0 ){
     echo 'エラーが発生したため、記事一覧画面へ飛びます</br>';
     header("refresh:5;url=./index.php");
     exit;
  }
  //IDが想定通りの設定の場合
  if ($empty_flg == 1 && $numeric_flg == 1 && $overzero_flg == 1 && $int_flg == 1 ) {
  //POST送信が来た場合
     if ($_SERVER["REQUEST_METHOD"] === "POST") {
       try{
        $dbh = dbconnect();
        $dtmt = $dbh->prepare("DELETE FROM t_blogs WHERE id = :id");
        $dtmt->bindParam(':id', $ID, PDO::PARAM_INT);
        $dtmt->execute();
        //クエリ実行に失敗した場合
        if( $dtmt->errorCode() != '00000'){
          throw new Exception("削除に失敗しました。");
        }
        //db切断
        $dbh = null;
        header( "Location: ./index.php" ) ;
      }catch(Exception $e){
        echo  $e->getMessage();
        exit;
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
  display_heder()
 ?>
  <body>
   <form action="./del.php?ID=<?php echo $ID;?>" method="post">
     <a>削除しますか?</a></br></br>
     <a>--------------------------------------</a></br>
     <a><?php echo $data['title'];?></a></br>
     <a>--------------------------------------</a></br>
     <a><?php echo $data['content'];?></a></br></br>
     <a><?php echo $data['created_at'];?></a></br>
     <a><?php
          if($data['created_at'] != $data['updated_at']){
            echo $data['updated_at'].PHP_EOL;
            echo '</br>';
          }
        ?></a></br>
     <input type="submit"  value="削除"></br></br>
   </form>
   <a href="./view.php?ID=<?php echo $ID;?>">戻る</a>
 </body>
</html>
