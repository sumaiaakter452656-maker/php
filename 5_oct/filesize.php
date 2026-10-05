<?php
$book ="../phpbook.pdf";
$byte = filesize($book);

$kb =round($byte/1024, 2);
echo $kb;
?>