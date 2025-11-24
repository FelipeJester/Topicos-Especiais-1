<?php

namespace App\Core;

class Validator
{
    private $errors = [];

    /**
     * Valida um array de dados com base em um conjunto de regras.
     * @param array $data Dados a serem validados (ex: $_POST)
     * @param array $rules Regras de validação (ex: ['campo' => 'required|numeric|min:1'])
     * @return bool
     */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    list($rule, $paramsString) = explode(':', $rule, 2);
                    $params = explode(',', $paramsString);
                }

                $method = 'validate' . ucfirst($rule);
                if (method_exists($this, $method)) {
                    if (!$this->$method($field, $value, $params)) {
                        // Para evitar duplicidade de erros, para o campo atual
                        break;
                    }
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Adiciona um erro de validação.
     * @param string $field
     * @param string $message
     */
    private function addError(string $field, string $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Retorna os erros de validação.
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    // --- Métodos de Validação ---

    private function validateRequired(string $field, $value): bool
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            $this->addError($field, "O campo {$field} é obrigatório.");
            return false;
        }
        return true;
    }

    private function validateNumeric(string $field, $value): bool
    {
        if (!is_numeric($value)) {
            $this->addError($field, "O campo {$field} deve ser numérico.");
            return false;
        }
        return true;
    }

    private function validateMin(string $field, $value, array $params): bool
    {
        $min = $params[0] ?? 0;
        if (is_numeric($value) && $value < $min) {
            $this->addError($field, "O campo {$field} deve ser no mínimo {$min}.");
            return false;
        }
        return true;
    }

    private function validateMax(string $field, $value, array $params): bool
    {
        $max = $params[0] ?? PHP_INT_MAX;
        if (is_numeric($value) && $value > $max) {
            $this->addError($field, "O campo {$field} deve ser no máximo {$max}.");
            return false;
        }
        return true;
    }

    private function validateString(string $field, $value): bool
    {
        if (!is_string($value)) {
            $this->addError($field, "O campo {$field} deve ser uma string.");
            return false;
        }
        return true;
    }
}
