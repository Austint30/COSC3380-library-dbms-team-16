<?php

function fmtErrJson(){
    return json_encode(sqlsrv_errors());
}

?>