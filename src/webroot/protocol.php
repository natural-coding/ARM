<?php

include 'common.inc.php';

/**
 * Список всех протоколов из таблицы PROTOCOL_TABLE
 */
$q = $conn->query(<<<ENDMARKER
SELECT proto_ordnum, employee,
   DATE_FORMAT(test_date,'%d.%m.%y') as test_date_fmt,
   IF(values_ok_flag,'Да','Нет') as values_ok_flag_fmt
FROM pdo.protocol_table
ORDER BY proto_ordnum desc
ENDMARKER
);
showHeader('Список протоколов');
?>


<table width="100%" border="1" cellpadding="3">
<tr style="font-weight: bold">
<td>N п/п</td>
<td>Номер протокола</td>
<td>Дата выдачи</td>
<td>Ответственный</td>
<td>Соответствие</td>
</tr>


<?php

$rownum = 1;
while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
   $bgstyle = ($r['values_ok_flag_fmt'] === 'Нет' ? 'background-color: #f2f2f2' : '');

?>
   <tr>
      <td><?=$rownum++?></td>
      <td><?=htmlspecialchars($r['proto_ordnum'])?></td>
      <td><?=htmlspecialchars($r['test_date_fmt'])?></td>
      <td><?=htmlspecialchars($r['employee'])?></td>
      <td style="<?=$bgstyle?>"><?=htmlspecialchars($r['values_ok_flag_fmt'])?></td>
   </tr>
<?php
}
?>

</table>

<br>
<a href="http://localhost/proto_add.php" class="add-record-anchor">Добавить запись</a>

<?php

showFooter();