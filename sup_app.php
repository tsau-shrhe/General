﻿<?php
$userlog_id = $_COOKIE["userlog_id"];
$userlog_password = base64_decode($_COOKIE["userlog_password"]);
//權限查詢語法
if (!empty($userlog_id) && !empty($userlog_password)) {
    include("include/SQ.php");// 開啟資料庫連接
    $sql_user = "SELECT * FROM emp WHERE work_id LIKE '".$userlog_id."'";
    $row = mysql_fetch_row(mysql_query($sql_user)); // 執行SQL查詢
    if ($userlog_id == $row[0] && md5($userlog_password) == $row[2]) {
        //確認帳號密碼正確
        $user_id = $row[0] ; //寫入使用者工號
        $user_name = $row[1] ; //寫入使用者名稱
        $user_dep = $row[3]; //寫入使用者部門
        $user_pur = $row[4]; //寫入使用者權限
        mysql_close($db); // 關閉資料庫連接
    }else{
        mysql_close($db); // 關閉資料庫連接
    }
}
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
</head>
<body>
	<div data-role="page">
		<div data-role="header" class="hea">
			<a href="index.html#p2" target="_self" data-icon="back">返回</a>
			<h1>雜項用品</h1>
		</div>
		<form action="sup_cheak.php" method="post" data-ajax="false" style="margin:20px">
		<ul data-role="listview" data-inset="true">
        <li data-role="list-divider" style="text-align: center;font-size:large;">申請表</li>
        <li class="ui-field-contain">
        <div data-role="fieldcontain">
        <label for="name">申請人:</label>
        <input type="text" name="name" id="name" value="<?php  echo $user_name;  ?>" placeholder="*必填欄位!!" required>
        </div>
        <div data-role="fieldcontain">
        <label for="work_id">工號:</label>
        <input type="text" name="work_id" id="work_id" value="<?php  echo $user_id;  ?>" placeholder="*必填欄位!!" required>
        </div>
        <div data-role="fieldcontain">
        <label for="dep">部門:</label>
        <select name="dep" id="dep" data-native-menu="false" required >
         <?php if($user_dep <> "" ){
                        echo "<option value='".$user_dep."'>".$user_dep."</option>";
                }else{
                        echo  "<option value=''>*請選擇部門</option>";
                }
         ?>
          <option value="SMD">SMD</option>
          <option value="後製成">後製程</option>
          <option value="後製程(手插)">後製程(手插)</option>
          <option value="後製程(修整)">後製程(修整)</option>
          <option value="後製程(測試)">後製程(測試)</option>
          <option value="後製程(系統)">後製程(系統)</option>
          <option value="製本部">製本部</option>
          <option value="工程">工程</option>
          <option value="物料">物料</option>
          <option value="品保">品保</option>
          <option value="工務">工務</option>
          <option value="行政">行政</option>
          <option value="財務">財務</option>
          <option value="財務(人事)">財務(人事)</option>
          <option value="業務">業務</option>
        </select>
        </div><br/>

		<div class="ui-grid-solo" style="background-color: whitesmoke;border: 1px solid hsl(1, 14%, 89%);border-top-left-radius:5px ;border-top-right-radius:5px ;text-align: center;">
            <div class="ui-block-a"><h2>請選擇申領項目</h2></div>
        </div>
        <div class="ui-grid-solo" style="border: 1px solid hsl(1, 14%, 89%);text-align: center;">
            <div class="ui-block-a">
            <select name="sup" id="sup" data-native-menu="false">
              <option value="none">用品</option>
              <option value="垃圾袋">垃圾袋</option>
              <option value="A1白棉布">A1白棉布</option>
              <option value="掃把">掃把</option>
              <option value="畚斗">畚斗</option>
              <option value="拖把">拖把</option>
              <option value="肥皂">肥皂</option>
			</select>
            <select name="sup_num" id="sup_num" data-native-menu="false">
              <option value="none">數量</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
            </div>
        </div>

        <div class="ui-grid-a">
            <div data-role="fieldcontain">
                <label for="remark">備註:</label>
                <textarea name="remark" id="remark"></textarea>
            </div>
        </div>
		<div class="ui-grid-a">
		<div class="ui-block-a">
			<input type="submit" value="送出" name="send"  data-icon="arrow-r"/>
		</div>
		<div class="ui-block-b">
        	<input type="reset" value="重新填寫" name="del"  data-icon="delete"/>
        </div>
		</div>
        </li>
        </ul>
	</form>

	<div  data-role="footer" class="foot">
		<p class="foot_p">Copyright © 2016 科技股份有限公司</p>
	</div>
</div>

</body>
</html>
