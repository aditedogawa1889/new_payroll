<?php

namespace App\Database;

use Illuminate\Database\SqlServerConnection;
use App\Database\CustomQueryBuilder as Builder;
use Illuminate\Support\Facades\Crypt;
use App\Database\CustomEncryptor;
use App\Database\CryptableColumnsByTable;

class CustomSqlServerConnection extends SqlServerConnection
{
    public function query()
    {
        return new Builder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }

    public function select($query, $bindings = [], $useReadPdo = true)
    {
        $decryptableColumnsByTable = CryptableColumnsByTable::decryptableColumnsByTable();

        $results = parent::select($query, $bindings, $useReadPdo);

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
}
