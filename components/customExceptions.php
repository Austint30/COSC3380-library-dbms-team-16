<?php
    class SqlQueryException extends Exception {
        public function errorMessage() {
          //error message
          $errorMsg = 'SqlQueryException thrown. Error on line '.$this->getLine().' in '.$this->getFile()
          .': <b>'.$this->getMessage();
          return $errorMsg;
        }
      }
?>