<?php

	//XAMPPに接続
	// ステップ1.db接続
	$dsn='mysql:dbname=oneline_bbs;host=localhost:8080';//本来はIPアドレスを指定

	//接続するためのユーザー情報
	$user='root';
	$password='sp4p09y6';



	//本番環境に接続
	// $dsn='mysql:dbname=LAA0731422-homework;host=mysql110.phy.lolipop.lan';

	// $user='LAA0731422';
 // 	$password='sp4p09y6';

 	//DB接続オブジェクトを作成
  $dbh=new PDO($dsn,$user,$password);
// 
  //接続したDBオブジェクトで文字コードutf8を使うように指定
  $dbh->query('SET NAMES utf8');


?>