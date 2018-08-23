<?php
include 'phpqrcode.php';
QRcode::png('http://www.cnblogs.com/txw1958/', $outfile=false, $level=QR_ECLEVEL_L, $size=8, $margin=1, $saveandprint=false);

?>