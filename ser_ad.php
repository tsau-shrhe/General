﻿<?php

//判斷權限及輸入使用者資料
include("include/Cheak_user_pur.php");

$page_record = 10; // 每一頁顯示的記錄筆數

// 取得URL參數的頁數
if (isset($_GET["Pages"])) $pages = $_GET["Pages"];
else                       $pages = 1;

//判斷使用者權限並給予搜尋範圍
switch($user_pur){
    case'1':
        $sql = "SELECT * FROM service ORDER BY id DESC";
        $titeltext = '報修內容變更';
        break;
    case'2':
        $sql = "SELECT * FROM service WHERE name LIKE '%".$user_name."%' ORDER BY id DESC";
        $titeltext = '報修紀錄';
        break;
}

$rows = mysql_query($sql); // 執行SQL查詢

$total_record = mysql_num_rows($rows); // 取得記錄數

$total_pages = ceil($total_record/$page_record);// 計算總頁數

$star_record = ($pages - 1)*$page_record;// 計算這一頁第1筆記錄的位置

mysql_close($db); // 關閉資料庫連接


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no " />
    <!-- 主要CSS -->
    <link rel="stylesheet" href="css/body.css" />
    <!-- jquery mobile CSS -->
    <link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css" />
    <!-- 網站ICON -->
    <link rel="shortcut icon" href="img/logo.png">
    <!-- jquery js -->
    <script src="js/jquery-1.12.3.min.js"></script>
    <!-- jquery mobile js -->
    <script src="js/jquery.mobile-1.4.5.min.js"></script>
    <!-- 滾動陰影 -->
    <script src="js/shadow.js"></script>
    <!-- 側頁表單 -->
    <script src="js/panel.js"></script>
</head>
<body>
<div data-role="page">
<div data-role="header" class="hea">
<div data-role="controlgroup" data-type="horizontal" class="ui-btn-left">
<a href="index.html" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-back" target="_self">返回</a>
<a href="#panelmenu" class="ui-btn ui-shadow ui-corner-all ui-icon-bars ui-btn-icon-notext">選單</a>
</div>
	<H1>報修管理</H1>
<div data-role="navbar">
	<ul>
		<li><a href="ser_ad_key_search.php?key=%E5%BE%85%E4%BF%AE%E4%B8%AD" target="_self">待修</a></li>
		<li><a href="ser_ad_key_search.php?key=%E5%B7%B2%E4%BF%AE%E5%BE%A9" target="_self">已修復</a></li>
	</ul>
</div>
    <?php
            echo "<div data-role='controlgroup' data-type='horizontal' class='ui-mini ui-btn-right'>";
            if ( $pages > 1 )  // 顯示上一頁
            echo "<a href='ser_ad.php?Pages=".($pages-1).
            "' target='_self' class='ui-btn ui-icon-arrow-l ui-btn-icon-notext ui-corner-all'>　</a>";

            echo "<select data-native-menu='false' data-icon='false' onChange='self.location.href=this.value'>";
            echo "<form action='' method='POST' data-ajax='false'>";
            echo "<option value='".$pages."'>".$pages."</option>";
            for ( $i = 1; $i <= $total_pages; $i++ )
            if ($i != $pages) // 顯示頁碼
            echo "<option value='ser_ad.php?Pages=".$i."'>".$i."</option>";
            echo "</form></select>";

            if ( $pages < $total_pages )  // 顯示下一頁
            echo "<a href='ser_ad.php?Pages=".($pages+1).
            "' target='_self' class='ui-btn ui-icon-arrow-r ui-btn-icon-notext ui-corner-all'>　</a>";

            echo "</div>";
            ?>
</div>
<div data-role="content" >
      <div class="titel"><span class="titeltext"><?php echo $titeltext; ?></span></Div>
        <ul data-role="listview" data-split-icon="gear" data-split-theme="a" data-inset="true">
      <?php
    if ($total_record > 0) { // 有記錄
    $num = $star_record + $page_record;

    //顯示指定記錄
    for ($i = $star_record;$i < $num && $i < $total_record; $i++ ) {
        $id = mysql_result($rows, $i, "id");
        $app_date = mysql_result($rows, $i, "app_date");
        $work_id = mysql_result($rows, $i, "work_id");
        $name = mysql_result($rows, $i, "name");
        $dep = mysql_result($rows, $i, "dep");
        $err_cat = mysql_result($rows, $i, "err_cat");
        $err_name = mysql_result($rows, $i, "err_name");
        $location = mysql_result($rows, $i, "location");
        $err_con = mysql_result($rows, $i, "err_con");
        $repair_details = mysql_result($rows, $i, "repair_details");
        $ser_sta = mysql_result($rows, $i, "ser_sta");

        if( is_file('thumb/'.$id.'.jpg') ){
            $img = $id;
        }else{
            $img = "noimg";
        }

    switch($ser_sta) {
        case '待修中':
            $color = 'ff0000' ;
            break;
        case '已修復':
            $color = '0000FF' ;
            break;
    }
    switch ($user_pur) {
        case '1':
            echo "<li data-icon='delete'>";
            echo "<a href='ser_updata.php?id=".$id."'>";
            echo "<img src='thumb/".$img.".jpg'>";
            echo "<h1>".$id.'.'.$app_date."</h1>";
            echo "<p class='ui-li-aside' style='color:#".$color."'><strong>".$ser_sta."</strong></p>";
            echo "<P><strong>".$name."(".$work_id.")</strong> 報修了: <strong style='color: blue;'>".$err_name."(".$err_cat.")".
            "</strong></P><P>故障概況:<strong style='color: blue;'>".$err_con."</strong></p>
            <P>地點:<strong style='color: blue;'>".$location."</strong></P>
            <P>維修細節:<strong style='color: blue;'>".$repair_details."</strong></P>";
            echo "<a href='ser_delete.php?id=".$id."' data-position-to='window'></a>";
            echo '</a></li>';
        break;
        case '2':
            echo "<li>";
            echo "<img src='thumb/".$img.".jpg'>";
            echo "<h1>".$id.'.'.$app_date."</h1>";
            echo "<p class='ui-li-aside' style='color:#".$color."'><strong>".$ser_sta."</strong></p>";
            echo "<P><strong>".$name."</strong> 報修了: <strong style='color: blue;'>".$err_name."(".$err_cat.")".
            "</strong></P><P>故障概況:<strong style='color: blue;'>".$err_con."</strong></p>
            <P>地點:<strong style='color: blue;'>".$location."</strong></P><P>維修細節:<strong style='color: blue;'>".$repair_details."</strong></P>";
            echo '</li>';
          break;
    }
  }
}
      ?>
      </UL>
      <?php
        //如搜尋資料數為零
        if ($total_record == 0){
            echo "<h1>無相關搜尋資料</h1>";
        }
      ?>
</div>
<div data-role="footer" class="foot" data-position="fixed" data-position-to="origin" >
<form action="ser_ad_key_search.php" method="GET" data-ajax="false">
<table>
  <tr>
    <td style="width: 100%;" ><input data-type="search" name="key" data-mini="true" id="key"  placeholder="請輸入關鍵字 ex:工號,姓名等等..." required ></td>
    <td ><input type="submit" data-mini="true" value="搜尋" name="send"  data-icon="search"></td>
  </tr>
</table>
</form>
</div>
<div data-role="panel" id="panelmenu" data-position="left" data-display="overlay" data-theme="a">
        <iframe src="logmenu.php" height="510px" width="100%"  style="margin: 0px auto;border:none" ></iframe>
</div>
</div>
</body>
</html>
