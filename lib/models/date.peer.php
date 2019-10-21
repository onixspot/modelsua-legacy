<?php

class date_peer extends db_peer_postgre
{
	private $month = array(
		1 => 'Январь',
		2 => 'Февраль',
		3 => 'Март',
		4 => 'Апрель',
		5 => 'Май',
		6 => 'Июнь',
		7 => 'Июль',
		8 => 'Август',
		9 => 'Сентябрь',
		10 => 'Октябрь',
		11 => 'Ноябрь',
		12 => 'Декабрь'
	);
	
	public static function instance()
	{
		return parent::instance('date_peer');
	}
	
	public function get_month($month_id)
	{
		return $this->month[$month_id];
	}
}

?>
