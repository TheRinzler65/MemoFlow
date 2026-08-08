<?php

namespace App\Helpers;

use App\Helpers\Error;

class Validator
{
  /**
   * Valide un tableau de données selon des règles spécifiques
   * @param array $data Les données à valider (ex: $_POST)
   * @param array $rules Les règles à appliquer (ex: ['email' => 'required|email'])
   */
  public static function validate(array $data, array $rules): array
  {
    $errors = [];
    $validatedData = [];

    foreach ($rules as $field => $fieldRules) {
      $value = isset($data[$field]) ? trim(htmlentities($data[$field])) : '';

      $validatedData[$field] = $value;

      $rulesArray = explode('|', $fieldRules);

      foreach ($rulesArray as $rule) {
        $ruleParam = '';
        if (str_contains($rule, ':')) {
          [$rule, $ruleParam] = explode(':', $rule);
        }

        if ($rule === 'required' && empty($value)) {
          $errors[] = "Le champ '$field' est obligatoire.";
          break;
        }

        if (empty($value)) {
          continue;
        }

        if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $errors[] = "Le champ '$field' doit être une adresse e-mail valide.";
        }

        if ($rule === 'min' && \strlen($value) < (int)$ruleParam) {
          $errors[] = "Le champ '$field' doit contenir au moins $ruleParam caractères.";
        }

        if ($rule === 'matches') {
          $compareValue = isset($data[$ruleParam]) ? trim(htmlentities($data[$ruleParam])) : '';
          if ($value !== $compareValue) {
            $errors[] = "Le champ '$field' doit correspondre au champ '$ruleParam'.";
          }
        }

        if ($rule === 'starts_with' && !str_starts_with($value, $ruleParam)) {
          $errors[] = "Le champ '$field' doit commencer par '$ruleParam'.";
        }
      }
    }

    if (!empty($errors)) {
      Error::sendErrors($errors, 400);
    }

    return $validatedData;
  }
}
