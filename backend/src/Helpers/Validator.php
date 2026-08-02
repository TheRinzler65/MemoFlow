<?php

namespace App\Helpers;

class Validator
{
  /**
   * Valide un tableau de données selon des règles spécifiques
   * @param array $data Les données à valider (ex: $_POST)
   * @param array $rules Les règles à appliquer (ex: ['email' => 'required|email'])
   * @param string $redirectUrl L'URL de redirection en cas d'échec
   */
  public static function validate(array $data, array $rules, string $redirectUrl): array
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
          $errors[] = "The field '$field' is required.";
          break;
        }

        if (empty($value)) {
          continue;
        }

        if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $errors[] = "The field '$field' must be a valid email address.";
        }

        if ($rule === 'min' && \strlen($value) < (int)$ruleParam) {
          $errors[] = "The field '$field' must be at least $ruleParam characters.";
        }

        if ($rule === 'matches') {
          $compareValue = isset($data[$ruleParam]) ? trim(htmlentities($data[$ruleParam])) : '';
          if ($value !== $compareValue) {
            $errors[] = "The field '$field' must match the field '$ruleParam'.";
          }
        }

        // --- Nouvelle règle ajoutée ici ---
        if ($rule === 'starts_with' && !str_starts_with($value, $ruleParam)) {
          $errors[] = "The field '$field' must start with '$ruleParam'.";
        }
      }
    }

    if (!empty($errors)) {
      $_SESSION['errors'] = $errors;
      $_SESSION['old'] = $data;

      header("Location: $redirectUrl"); // Changer
      exit;
    }

    return $validatedData;
  }
}
