<?php

namespace App\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Crypt;
use App\Database\CustomEncryptor;
use App\Database\CryptableColumnsByTable;
use App\Http\Controllers\Util\IP\IPLocation;

class CustomQueryBuilder extends Builder
{
    protected function getTableFromQuery()
    {
        return $this->from;
    }

    public function get($columns = ['*'])
    {
        $decryptableColumnsByTable = CryptableColumnsByTable::decryptableColumnsByTable();

        $results = parent::get($columns);

        foreach ($results as $result) {
            foreach ($decryptableColumnsByTable as $table => $columns) {
                foreach ($columns as $column) {
                    if (property_exists($result, $column) && !is_null($result->$column)) {
                        try {
                            $result->$column = CustomEncryptor::deterministicDecrypt($result->$column);
                        }
                        catch(\Exception $ex) {
                            $result->$column = $result->$column;
                        }
                    }
                }
            }
        }

        return $results;
    }

    public function first($columns = ['*'])
    {
        $decryptableColumnsByTable = CryptableColumnsByTable::decryptableColumnsByTable();

        $result = parent::first($columns);

        if ($result) {
            foreach ($decryptableColumnsByTable as $table => $columns) {
                foreach ($columns as $column) {
                    if (property_exists($result, $column) && !is_null($result->$column)) {
                        try {
                            $result->$column = CustomEncryptor::deterministicDecrypt($result->$column);
                        }
                        catch(\Exception $ex) {
                            $result->$column = $result->$column;
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function insert(array $values)
    {
        if($this->from == "log_activity") {
            $IPLocation = new IPLocation();
            $IpLocation = $IPLocation->IpLocation();

            $ip_public = $IpLocation["ip"];
            $ip_local = request()->cookie('ip_local');
            $latitude = request()->cookie('latitude');
            $longitude = request()->cookie('longitude');
            $values["ip"] = $ip_public;
            $values["ip_local"] = $ip_local;
            $values["latitude"] = $latitude;
            $values["longitude"] = $longitude;
        }
        
        $encryptableColumnsByTable = CryptableColumnsByTable::encryptableColumnsByTable();

        if (array_key_exists($this->from, $encryptableColumnsByTable)) {
            $encryptableColumns = $encryptableColumnsByTable[$this->from];

            foreach ($encryptableColumns as $column) {
                if (isset($values[$column])) {
                    $values[$column] = CustomEncryptor::deterministicEncrypt($values[$column]);
                }
            }
        }

        return parent::insert($values);
    }

    public function update(array $values)
    {
        $encryptableColumnsByTable = CryptableColumnsByTable::encryptableColumnsByTable();

        if (array_key_exists($this->from, $encryptableColumnsByTable)) {
            $encryptableColumns = $encryptableColumnsByTable[$this->from];

            foreach ($encryptableColumns as $column) {
                if (isset($values[$column])) {
                    $values[$column] = CustomEncryptor::deterministicEncrypt($values[$column]);
                }
            }
        }

        return parent::update($values);
    }

    public function insertGetId(array $values, $sequence = null)
    {
        $encryptableColumnsByTable = CryptableColumnsByTable::encryptableColumnsByTable();

        if (array_key_exists($this->from, $encryptableColumnsByTable)) {
            $encryptableColumns = $encryptableColumnsByTable[$this->from];

            foreach ($encryptableColumns as $column) {
                if (isset($values[$column])) {
                    $values[$column] = CustomEncryptor::deterministicEncrypt($values[$column]);
                }
            }
        }

        return parent::insertGetId($values, $sequence);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $encryptableColumnsByTable = CryptableColumnsByTable::encryptableColumnsByTable();

        if (is_string($column)) {
            if (func_num_args() === 2) {
                $value = $operator;
                $operator = '=';
            }

            $parts = explode('.', $column);
            $pureColumn = end($parts);

            $table = $this->getTableWithoutAlias();

            if (array_key_exists($table, $encryptableColumnsByTable)) {
                $encryptableColumns = $encryptableColumnsByTable[$table];

                if (in_array($pureColumn, $encryptableColumns)) {
                    if (!is_null($value)) {
                        $value = CustomEncryptor::deterministicEncrypt($value);
                    }
                }
            }
        }

        return parent::where($column, $operator, $value, $boolean);
    }

    protected function getTableWithoutAlias()
    {
        $parts = preg_split('/\s+as\s+/i', $this->from);
        return $parts[0] ?? $this->from;
    }
}
