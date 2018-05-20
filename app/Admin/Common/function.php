<?php
/**
* 数据转csv格式的excle
* @param  array $data      需要转的数组
* @param  string $filename 生成的excel文件名
*      示例数组：
$a = array(
'1,2,3,4,5',
'6,7,8,9,0',
'1,3,5,6,7'
);
*/
function create_csv($data,$header=null,$filename='simple.csv'){
    // 如果手动设置表头；则放在第一行
    if (!is_null($header)) {
        array_unshift($data, $header);
    }
    // 防止没有添加文件后缀
    $filename=str_replace('.csv', '', $filename).'.csv';
    ob_clean();
    Header( "Content-type:  application/octet-stream ");
    Header( "Accept-Ranges:  bytes ");
    Header( "Content-Disposition:  attachment;  filename=".$filename);
    foreach( $data as $k => $v){
        // 如果是二维数组；转成一维
        if (is_array($v)) {
            $v=implode(',', $v);
        }
        // 替换掉换行
        // $v=preg_replace('/\s*/', '', $v);
        // 解决导出的数字会显示成科学计数法的问题
        $v=str_replace(',', "\t,", $v);
        // 转成gbk以兼容office乱码的问题
        echo iconv('UTF-8','GBK',$v)."\t\r\n";
    }
}

function getIndex($arr,$field){
    
    $count=0;
    
    
    foreach ($arr as $key => $value) {
        if($key==$field){
            return $count;
        }
        $count++;
    }
    
    return -1;
    
}

function array_insert (&$array, $position, $insert_array) {
    $first_array = array_splice ($array, 0, $position);
    $array = array_merge ($first_array, $insert_array, $array);
}