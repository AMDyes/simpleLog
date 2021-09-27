<?php
/**
 * Auth: UJB
 * Date: 2021/9/26 18:17
 * 添加项目url路径
 */

$name = $_POST['name'] ?? '';
$path = $_POST['path'] ?? '';
if (!$name) {
    result(500, '缺少参数');
}
$projectsFileName = __DIR__ . DIRECTORY_SEPARATOR . 'projects.txt';
// 如果还没有文件 写入文件
if (!file_exists($projectsFileName)) {
    $projectsFile = fopen($projectsFileName, 'w');
    $data         = json_encode([$name => $path], JSON_UNESCAPED_UNICODE);
    fwrite($projectsFile, $data);
    fclose($projectsFile);
}
// 读取文件
if (false === ($projects = file_get_contents($projectsFileName))) {
    result(500, '文件读取失败');
}
$projectArr = json_decode($projects, true) ? : [];
$projectArr = array_merge($projectArr, [$name => $path]);
// 空值删除
foreach ($projectArr as $k => $v) {
    if (empty($v)) {
        unset($projectArr[$k]);
    }
}
// 将数据json化存入
$json = json_encode($projectArr, JSON_UNESCAPED_UNICODE);
if (!file_put_contents($projectsFileName, $json)) {
    result(500, '文件写入失败');
}
result();
// *** //
function result($code = 200, $msg = 'ok')
{
    header('content-type:text/html;charset=json');
    exit(json_encode(compact('code', 'msg'), JSON_UNESCAPED_UNICODE));
}

function dd($data)
{
    print_r($data);
    exit();
}