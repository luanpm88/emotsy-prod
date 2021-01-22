<?php

namespace Acelle\Library\Traits;

trait QueryHelper {
	public function scopePerPage($query, $size = 1000, $callback)
	{
		$count = $query->count();
        $pages = (int)ceil($query->count()/$size);
        for ($i = 0; $i < $pages; $i += 1) {
            $offset = $size * $i;
            echo "page $i";
            $callback($query->limit($size)->offset($offset));
        }
	}

	public function createTemporaryTableFromArray($name, $data, $fields, $callback)
	{
		try {
			DB::beginTransaction();

			$tableName = $name;
        	$table = table($tmpTableName);
            $fieldsSql = implode(',', $fields));

        	DB::statement("DROP TABLE IF EXISTS {$table};
                       CREATE TABLE {$table}({$fieldsSql});");

        	// Actually insert data
        	DB::table($tableName)->insert($data);

        	// Pass to the controller for handling
        	$callback($tableName); // Note: it is without prefix
    		
    		// Cleanup
            DB::statement("DROP TABLE IF EXISTS {$table};");

            // It is all done
    		DB::commit();
        } catch (\Exception $e) {
            // finish the transaction
            DB::rollBack();
            throw $e;
        }
	}
}