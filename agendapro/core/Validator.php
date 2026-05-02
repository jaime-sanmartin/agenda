<?php
// core/Validator.php
class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        $this->errors = [];
        foreach ($rules as $field => $ruleSet) {
            $value = isset($data[$field]) ? $data[$field] : null;
            $rules = explode('|', $ruleSet);
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }
        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule) {
        if ($rule === 'required' && empty($value)) {
            $this->errors[$field][] = "El campo {$field} es requerido";
        }
        
        if (strpos($rule, 'min:') === 0) {
            $min = substr($rule, 4);
            if (strlen($value) < $min) {
                $this->errors[$field][] = "El campo {$field} debe tener al menos {$min} caracteres";
            }
        }
        
        if (strpos($rule, 'max:') === 0) {
            $max = substr($rule, 4);
            if (strlen($value) > $max) {
                $this->errors[$field][] = "El campo {$field} no puede exceder {$max} caracteres";
            }
        }
        
        if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "El campo {$field} debe ser un email válido";
        }
        
        if ($rule === 'numeric' && !is_numeric($value)) {
            $this->errors[$field][] = "El campo {$field} debe ser numérico";
        }
        
        if ($rule === 'date' && !strtotime($value)) {
            $this->errors[$field][] = "El campo {$field} debe ser una fecha válida";
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
?>