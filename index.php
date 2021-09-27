<?php

$fileName = __DIR__ . DIRECTORY_SEPARATOR . 'projects.txt';
$projects = file_get_contents($fileName);
$arr      = json_decode($projects, JSON_UNESCAPED_UNICODE);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        input{
            width:65px;
        }
        select{
            width:80px;
        }
    </style>
</head>
<body>
<div style="color:azure;text-align:center">
    <form action="index.php" method="get">
        Project: <select name="url" id="">

            <?php
            foreach ($arr as $k => $v) {
                echo "<option value=\"{$v}\">{$k}</option>";
            }
            ?>
        </select>
        Ym: <input type="text" name="ym" id="" value="<?php echo date("Ym",time()) ?>">
        day:<input type="text" name="day" id="" value="<?php echo date('d',time()) ?>">
        lines:<input type="text" name="lines" value="<?php echo 20 ?>">
        <button type="submit">提交</button>
    </form>
    <form onsubmit="return false" action="#" method="post">
        项目name: <input type="text" name="name" id="name" style="width:100px">
        项目path: <input type="text" name="path" id="path" style="width:140px" placeholder="example: devtooth" autocomplete="off">
        <button onclick="login()" id="submit">添加项目</button>
    </form>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<script type="text/javascript">
    function login() {
        var data = Object();
        data.name = $("#name").val()
        data.path = $("#path").val()
        $.ajax({
            //几个参数需要注意一下
            type: "POST",//方法类型
            dataType: "json",//预期服务器返回的数据类型
            url: "add_project.php",//url
            data: data,
            success: function (result) {
                console.log(result);//打印服务端返回的数据(调试用)
                if (result.code === 200) {
                    alert(result.msg)
                    window.location.reload()
                } else {
                    console.log(data)
                    alert(result.msg)
                    window.location.reload()
                }
            },
            error: function () {
                alert("异常！");
            }
        });
    }
</script>
</html>
<?php
/**
 * Auth: UJB
 * Date: 2021/9/1 17:20
 */
echo "<body style='background-color:#2B2B2B;'>";
echo "<pre>";
$ym      = date('Ym');
$day     = date('d');
$url     = current($arr);
if (!$url){
    echo "<span style='height:30px; color:#A9B7C6; line-height: 1px; text-align:left'><b>暂无日志</b></span>";
    die();
}
$ym      = !empty($_GET['ym']) ? $_GET['ym'] : $ym;
$day     = !empty($_GET['day']) ? $_GET['day'] : $day;
$url     = !empty($_GET['url']) ? $_GET['url'] : $url;
$lines   = !empty($_GET['lines']) ? $_GET['lines'] : 20;
$fileUrl = "http://log.{$url}.sgtimes.cn/{$ym}/{$day}_error.log";
$file    = fopen($fileUrl, 'r');
//

$arr = [];
$i   = 0;
// 输出到数组
while (!feof($file)) {
    $arr[$i] = fgets($file);
    $i++;
}
fclose($file);
$arr = array_filter($arr);
$arr = array_slice($arr, -$lines);
echo "<ul>";
foreach ($arr as $k => $item) {
    // $color = $k % 2 == 0 ? '#2B2B2B' : '#2B2B2B';\
    echo "<p style='height:40px;font-size:15px; margin:0; text-align:left'>
            <span style='height:30px; color:#A9B7C6; line-height: 1px; text-align:left'><b>$item</b></span> </p>";
}
echo '</body>';
?>
