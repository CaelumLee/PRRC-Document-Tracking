<?php
namespace App;

class ValidationExceptionExcel{

    public function error($id, $message){  
        $error = \Illuminate\Validation\ValidationException::withMessages([
        $id => [$message],
        ]);
        return $error;
    }
}

?>