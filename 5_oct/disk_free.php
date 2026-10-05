<?php
$drive = 'c:';
//printf("Remaining MB on %s: %.2f", $drive,
//round((disk_free_space($drive) / 1048576), 2));

$free = disk_free_space($drive);
echo round($free / 1048576/1024, 2);
?>