<?php

namespace App\Libraries;

class UniqueChecker
{
    /**
     * Проверяет уникальность полей или комбинаций полей в модели.
     *
     * @param \CodeIgniter\Model $model  Модель для проверки уникальности.
     * @param array              $data   Массив входных данных в формате ['field_name' => 'value'].
     * @param array              $fields Массив полей для проверки ['field1', 'field2', 'field1+field2'].
     *
     * @return array Массив результатов проверки уникальности ['field1' => true/false, 'field1+field2' => true/false].
     */
    public function checkUnique(\CodeIgniter\Model $model, array $data, array $fields): array {
        $results = [];

        foreach ($fields as $field) {
            if (strpos($field, '+') !== false) {
                $fieldParts = explode('+', $field);
                $where = [];

                foreach ($fieldParts as $part) {
                    if (isset($data[$part])) {
                        $where[$part] = $data[$part];
                    } else {
                        $results[$field] = false; // Если нет данных для проверки
                        continue 2; // Переходим к следующему полю
                    }
                }

                $exists = $model->where($where)->first();
                $results[$field] = $exists === null;
            } else {
                if (isset($data[$field])) {
                    $exists = $model->where($field, $data[$field])->first();
                    $results[$field] = $exists === null;
                } else {
                    $results[$field] = false; // Если нет данных для проверки
                }
            }
        }

        return $results;
    }
}
