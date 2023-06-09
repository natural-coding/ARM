<?php

$arr = [];
insertProtocolRecord($arr);
print_r($arr);

function insertProtocolRecord(array &$p_outResult)
{
   $p_outResult['status'] = 'success';
   $p_outResult['message'] = 'message!';
}