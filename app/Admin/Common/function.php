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
    // ob_clean();s
    Header( "Content-type:  application/octet-stream ");
    Header( "Accept-Ranges:  bytes ");
    Header( "Content-Disposition:  attachment;  filename=".$filename);
    foreach( $data as $k => $v){
        // 如果是二维数组；转成一维
        if (is_array($v)) {
            $v=implode(',', $v);
        }
        // 替换掉换行
        $v=preg_replace('/\s*/', '', $v);
        // 解决导出的数字会显示成科学计数法的问题
        $v=str_replace(',', "\t,", $v);
        // 转成gbk以兼容office乱码的问题
        echo iconv('UTF-8','GBK',$v)."\t\r\n";
        // echo $v;
        // echo '<hr/>';
    }
    // dump($data);
}

/**
* 数组转xls格式的excel文件
* @param  array  $data      需要生成excel文件的数组
* @param  string $filename  生成的excel文件名
*      示例数据：
$data = array(
array(NULL, 2010, 2011, 2012),
array('Q1',   12,   15,   21),
array('Q2',   56,   73,   86),
array('Q3',   52,   61,   69),
array('Q4',   30,   32,    0),
);
*/
function create_xls($data,$filename='simple.xls'){
    ini_set('max_execution_time', '0');
    Vendor('PHPExcel.PHPExcel');
    $filename=str_replace('.xls', '', $filename).'.xls';
    $phpexcel = new PHPExcel();
    $phpexcel->getProperties()
    ->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");
    $phpexcel->getActiveSheet()->fromArray($data);
    $phpexcel->getActiveSheet()->setTitle('Sheet1');
    $phpexcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=$filename");
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $objwriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
    $objwriter->save('php://output');
    exit;
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