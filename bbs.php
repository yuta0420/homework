<?php

	
  require('dbconnect.php');

  //削除ボタンが押されたとき
  if (isset($_GET['action'])&&($_GET['action']=='delete')){
  	$deletesql = sprintf('UPDATE `posts` SET `delete_flag` = 1 WHERE `id`=%s',$_GET['id']);

  	//SQL文の実行
    $stmt=$dbh->prepare($deletesql);
    $stmt->execute();

  }


  //LIKEが押されたときの処理
  	if(isset($_GET['action'])&&($_GET['action']=='like')){
  		//アップデート文でLIKEの数をカウントアップ（インクリメント）
  		$likesql = sprintf('UPDATE `posts` SET `likes` = `likes`+1 WHERE `id`=%s',$_GET['id']);

  	//SQL文の実行
    $stmt=$dbh->prepare($likesql);
    $stmt->execute();
  	}


  //POST送信が行われたら、下記の処理を実行
  //テストコメント
  if(isset($_POST['nickname']) && !empty($_POST['nickname'])){


  // }else{
    $sql = "INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES ('".$_POST['nickname']."','".$_POST['comment']."',now())";
    //SQL文（INSERT文）
    // $sql = sprintf("INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES (\'%s\',\'%s\',now())",$_POST['nickname'],$_POST['comment']);
    // $sql .= " VALUES ('".$_POST['nickname']."','".$_POST['comment']."',now())";


	//SQL文の実行
    $stmt=$dbh->prepare($sql);
    $stmt->execute();

	}

	

  if(isset($_POST['search_text']) && !empty($_POST['search_text'])){

  	//SQL文作成（SELECT文）
	$sql='SELECT*FROM `posts` WHERE `delete_flag`=0 AND`nickname` LIKE "%'.$_POST['search_text'].'%"';
  }
  else if($_POST["restart"]=="valid"){
  	$sql ='SELECT*FROM `posts` WHERE `delete_flag`=0 ORDER BY id DESC';
  }
  else{
	//SQL文(SELECT文)
 	$sql ='SELECT*FROM `posts`WHERE `delete_flag`=0 ORDER BY id DESC';
	}
 
	//SQL文実行
	$stmt=$dbh->prepare($sql);
	$stmt->execute();

  //格納する変数の初期化
  $posts = array();

  while(1){
    //実行結果として得られたデータを取得
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if($rec==false){
      break;
    }
    // 取得したデータを配列に格納しておく
    $posts[] = $rec;
  }

  //DB切断
  $dbh=null;

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
<!--                   <li class="hidden">
                      <a href="#page-top"></a>
                  </li>
                  <li class="page-scroll">
                      <a href="#portfolio">Portfolio</a>
                  </li>
                  <li class="page-scroll">
                      <a href="#about">About</a>
                  </li>
                  <li class="page-scroll">
                      <a href="#contact">Contact</a>
                  </li> -->
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>



  <!-- つぶやき -->
  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
        <form action="bbs.php" method="post">
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control"
                       id="validate-text" placeholder="nickname" required>

              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
            
          </div>

          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>

          <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
        </form>



        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <!-- あいまい検索 -->
        <form action="bbs.php" method="post">
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="search_text" class="form-control"
                       id="validate-text" placeholder="nickname" required>

              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
            
          </div>

          <button type="submit" class="btn btn-primary col-xs-12" disabled>ニックネームで検索する</button>
        </form>

        <form>
        <input name="restart" type="hidden" value="valid">
        <button type="submit" class="btn btn-primary col-xs-12" >検索をリセットする</button>
        </form>

       
      </div>

      <div class="col-md-8 content-margin-top">

        <div class="timeline-centered">

        <?php
      	//指定した配列のデータ数分繰り返しを行う
      	foreach ($posts as $post_each) {
      	?>

        <article class="timeline-entry">

            <div class="timeline-entry-inner">

                <div class="timeline-icon bg-success">
                    <i class="entypo-feather"></i>
                    <i class="fa fa-cogs"></i>
                </div>

                <div class="timeline-label">
                    
                	<?php

                	//string型からDatetime型へ変換
                	$created=strtotime($post_each['created']);

                	//書式を変換
                	$created=date('Y年m月d日　H時i分s秒',$created);
        			echo '<h2><a href="#">'.$post_each['nickname'].'</a><span>'.$created.'</span></h2>';
        			echo '<p>'.$post_each["comment"].'</p>';
        			?>
        			<p >
        			<a href="bbs.php?action=like&id=<?php echo $post_each['id']; ?>"><i class="fa fa-thumbs-o-up fa-lg"></i>LIKE </a><?php echo $post_each['likes'];?>
        			</p>
        			<p id="icon">
        			<a onclick="return confirm('本当に削除しますか？');" href="bbs.php?action=delete&id=<?php echo $post_each['id'];?>" ><i class="fa fa-trash fa-lg"></i></a>
        			</p>
                </div>
            </div>

        </article>

        <?php
        }
    	?>

        <article class="timeline-entry begin">

            <div class="timeline-entry-inner">

                <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                    <i class="entypo-flight"></i> +
                </div>

            </div>

        </article>

      </div>

    </div>
  </div>





  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>



