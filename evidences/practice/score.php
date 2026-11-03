<?php
$students = array(
    "Rahim"  => 85,
    "Karim"  => 72,
    "Sumon"  => 91,
    "Nadia"  => 68,
    "Fatema" => 79
);


$maxScore = "";
$topStudent = "";

foreach ($students as $name => $score) {
    if ($score > $maxScore) {
        $maxScore = $score;
        $topStudent = $name;
    }
}
?>
<table border="1" cellpadding="8">
    <tr>
        <th>Student Name</th>
        <th>Score</th>
    </tr>
    <?php foreach ($students as $name => $score) { ?>
    <tr>
        <td><?php echo $name; ?></td>
        <td><?php echo $score; ?></td>
    </tr>
    <?php } ?>
</table>

Highest Score:<?php echo $maxScore; ?><br>
Top Student:<?php echo $topStudent; ?>