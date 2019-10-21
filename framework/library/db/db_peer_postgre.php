<?

load::system('db/db_peer');

abstract class db_peer_postgre extends db_peer
{
	public function insert( $data, $ignore_duplicate = false )
	{
		$insert_data = array();
		$insert_columns = array();
		foreach ( $data as $column => $value )
		{
			$insert_data[] = ":{$column}";
			$insert_colunms[] = '"' . $column . '"';
		}

		$sql = 'INSERT ' . ( $ignore_duplicate ? ' IGNORE ' : '' ) . ' INTO ' . $this->table_name .
			' (' . implode(', ', $insert_colunms) . ') VALUES(' . implode(', ', $insert_data) . ')' .
			( $this->primary_key ? ' RETURNING ' . $this->primary_key : '' );

		if ( $this->primary_key )
		{
			$pk = db::get_scalar($sql, $data, $this->connection_name);
			$this->reset_item($pk);
			
			return $pk;
		}
		
		db::exec($sql, $data, $this->connection_name);
	}

	public function update( $data, $keys = null )
	{
		if ( !$keys )
		{
			$keys = array($this->primary_key => $data[$this->primary_key]);
		}

		$data = array_merge($data, $keys);

		$keys_data = array();
		foreach ( $keys as $column => $value )
		{
			$keys_data[] = "\"{$column}\" = :{$column}";
		}

		$update_data = array();
		foreach ( $data as $column => $value )
		{
			if ( !array_key_exists($column, $keys) )
			{
				$update_data[] = "\"{$column}\" = :{$column}";
			}
		}
		db::exec(
			'UPDATE ' . $this->table_name . ' SET ' . implode(', ', $update_data) . ' WHERE ' . implode(' AND ', $keys_data) ,
			$data,
			$this->connection_name
		);

		$this->reset_item( $data[$this->primary_key], $data );
	}

	public function delete_item( $primary_key )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE ' . $this->primary_key . ' = :id';
		$bind = array('id' => $primary_key);

		db::exec( $sql, $bind, $this->connection_name );

		$this->reset_item($primary_key);
	}
}