<?php
/**
 * Добавление нового протокола
 * Скрипт может вызываться через Ajax => POST-запрос для вставки записи в БД
 * или из браузера => GET-запрос для получения формы добавления нового протокола
 * 
 */

include 'common.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
   /**
    * Object example: {"proto_ordnum":"854"}
    */
   $jsonRequestBody = file_get_contents('php://input');

   $resultArray = array(
        'status' => '',
        'message' => '',
        'info' => []
    );

   $formVarsJson = json_decode($jsonRequestBody);

   if (checkInputJson($formVarsJson,$resultArray))
      insertProtocolRecord($formVarsJson,$resultArray);

   echo json_encode($resultArray,JSON_UNESCAPED_UNICODE);
}
else
{
   $responseForBrowser = <<<ENDMARKER
<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" href="protocol_add.css">
 <script src="protocol_add.js"></script>
 <title>Новый протокол</title>
</head>
<body>
<h2>Новый протокол</h2>

<form id="proto-add-form" method="POST" action="ajax_test.php">

<table border="0" cellpadding="2" cellspacing="20">
   
   <tr>
      <td><label for="proto_ordnum">Номер протокола</label></td>
      <td><input type="text" id="proto_ordnum" name="proto_ordnum"/></td>
   </tr>

   <tr>
      <td><label for="test_date">Дата выдачи</label></td>
      <td><input type="text" id="test_date" name="test_date" placeholder="ДД.ММ.ГГ"/></td>
   </tr>

   <tr>
      <td><label for="employee">Ответственный сотрудник</label></td>
      <td><input type="text" id="employee" name="employee"></td>
   </tr>

   <tr>
      <td><label for="values_ok_flag">Соответствие</label></td>
      <td>
         <select id="values_ok_flag" name="values_ok_flag">
            <option value="1" selected> Да
            <option value="0"> Нет
         </select>
      </td>
   </tr>

   <tr>
      <td colspan="2" align="left">
         <input type="submit" name="submit" value="Добавить" style="margin-top: 1rem">
      </td>
   </tr>

</table>

</form>  

</body>
</html>
ENDMARKER;

   print $responseForBrowser;
}

function insertProtocolRecord(stdClass $p_formVarsJsonValidated, array &$p_outResult)
{
   global $conn;

   /**
    * Принимает значения:
    * 'success'
    * 'failure'
    */
   $p_outResult['status'] = 'failure';

   /**
    * Принимает значения:
    * "Протокол N $input_proto_ordnum добавлен в БД";
    * "Протокол N $input_proto_ordnum уже существует";
    */
   $p_outResult['message'] = 'Неизвестная ошибка';

   $sql = 'SELECT count(proto_ordnum) as COUNT FROM pdo.protocol_table where proto_ordnum=?';
   $stmt = $conn->prepare($sql);
   $stmt->execute([$p_formVarsJsonValidated->proto_ordnum]);

   $rec = $stmt->fetch(PDO::FETCH_ASSOC);


   /**
    * Вставляем запись в БД, если протокол с таким номером не обнаружен
    */
   if ($rec['COUNT'] != 0) {
      $p_outResult['status'] = 'failure';
      $p_outResult['message'] = "Протокол N {$p_formVarsJsonValidated->proto_ordnum} уже существует";
   }
   else {
      $arrDMY = $p_formVarsJsonValidated->test_date_DayMonthYearArray;

      $Y = $arrDMY[2];
      $m = $arrDMY[1];
      $d = $arrDMY[0];

      /**
       * $STR_TO_DATE_SQL должен иметь вид:
       * STR_TO_DATE('30.07.2023','%d.%m.%Y')
       */
      $STR_TO_DATE_SQL = "STR_TO_DATE('$d.$m.$Y','%d.%m.%Y')";

      try {
         $sql =<<<ENDMARKER
INSERT INTO pdo.protocol_table (PROTO_ORDNUM, EMPLOYEE, TEST_DATE, VALUES_OK_FLAG)
VALUES (?,?,$STR_TO_DATE_SQL,?)
ENDMARKER;

         $stmt = $conn->prepare($sql);
         $stmt->execute(
            [
               $p_formVarsJsonValidated->proto_ordnum,
               $p_formVarsJsonValidated->employee,
               $p_formVarsJsonValidated->values_ok_flag
            ]
         );

         $p_outResult['status'] = 'success';
         $p_outResult['message'] = "Протокол N {$p_formVarsJsonValidated->proto_ordnum} добавлен в БД";
      }
      catch (PDOException $e)
      {
         $p_outResult['status'] = 'failure';
         $p_outResult['message'] = "Неизвестная ошибка: \n" . $e->getMessage();
      }
      catch (Throwable $e)
      {
         //
      }
   }
}

/**
 * Проверка введенных пользователем значений формы
 */
function checkInputJson(stdClass $p_inoutFormVarsJson, array &$p_outResult) : bool
{
   $p_outResult['info'] = [];

   $p_outResult['status'] = 'failure';
   $p_outResult['message'] = 'Проверьте данные';


   /**
    * Номер протокола. Проверка значения
    */

   if (!preg_match('#^\d+$#',$p_inoutFormVarsJson->proto_ordnum)) {
      $p_outResult['info'][] = array (
            'field' => 'proto_ordnum',
            'message' => 'Номер протокола должен содержать только цифры'
         );
    }

   /**
   * Ответственный сотрудник (ФИО сотрудника). Проверка значения
   */
    if (!preg_match('/^([А-ЯЁ]|[A-Z])([- \.А-Яа-яё]|[- \.A-Za-z])+$/u',$p_inoutFormVarsJson->employee)) {
      
      $p_outResult['info'][] = array (
            'field' => 'employee',
            'message' => 'ФИО сотрудника должно состоять из букв'
         );
    }

    if (mb_strlen($p_inoutFormVarsJson->employee) > 45) {

      $p_outResult['info'][] = array (
            'field' => 'employee',
            'message' => 'ФИО сотрудника не должно быть длинее 45 символов'
         );
    }

   /**
   * Дата выдачи. Проверка значения
   */
    if (!preg_match('#^\d{1,2}\.\d{1,2}\.\d{1,2}$#',$p_inoutFormVarsJson->test_date)) {

      $p_outResult['info'][] = array (
            'field' => 'test_date',
            'message' => 'Дата должна быть в формате ДД.ММ.ГГ'
         );
    }
    else {
      $arrayDayMonthYear = explode('.', $p_inoutFormVarsJson->test_date);
      $arrayDayMonthYear[2] += 2000;

      $year = $arrayDayMonthYear[2];
      $month = $arrayDayMonthYear[1];
      $day = $arrayDayMonthYear[0];

      if (!checkdate($month,$day,$year)) {

         $p_outResult['info'][] = array (
               'field' => 'test_date',
               'message' => 'Дата не прошла проверку'
            );
      }

      $p_inoutFormVarsJson->test_date_DayMonthYearArray = $arrayDayMonthYear;
    }

   /**
     * Соответствие (Да, Нет)
    */
   if (!($p_inoutFormVarsJson->values_ok_flag === '0' || $p_inoutFormVarsJson->values_ok_flag === '1')) {

      $p_outResult['info'][] = array (
            'field' => 'values_ok_flag',
            'message' => 'Значение поля "Соответствие" должно быть "Да" или "Нет"'
         );

    }





    if (empty($p_outResult['info'])) {
        $p_outResult['status'] = 'success';
        $p_outResult['message'] = 'Данные введены корректно';
    }

   // Для читаемости кода
   return (empty($p_outResult['info']) ? true : false);
}