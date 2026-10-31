<?php
$students = array(
    "Rahim"  => 85,
    "Karim"  => 72,
    "Sumon"  => 91,
    "Nadia"  => 68,
    "Fatema" => 79
);


$maxScore = 0;
$topStudent = "";

foreach ($students as $name => $score) {
    if ($score > $maxScore) {
        $maxScore = $score;
        $topStudent = $name;
    }
}
?>

<h2>Result Sheet</h2>
<table cellpadding="8">
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

<p>Highest Score: <b><?php echo $maxScore; ?></b></p>
<p>Top Student: <b><?php echo $topStudent; ?></b></p>