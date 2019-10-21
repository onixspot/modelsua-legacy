<?
class mailing_peer extends db_peer_postgre
{
        
        protected $table_name = 'mailing';
        
	const TYPE_EMAIL = 0;
	const TYPE_MESSAGE = 1;
	const TYPE_DRAFT = 2;
	
	/**
	 * @return user_auth_peer
	 */
	public static function instance()
	{
		return parent::instance( 'mailing_peer' );
	}
	
	public static function get_filter($id=NULL) {
	    $ret = array(
		'status'=>t('Статус'),
		'agency'=>t('Агенство'),
		'location'=>t('Страна'),
		'extended'=>t('Расширенные'),
		);
	    return ($id) ? (isset($ret[$id]) ? $ret[$id] : array() ) : $ret;
		    
	}
	
	public static function get_extended_filter($id=NULL) {
	    $ret = array(
		'not_approved'=>t('исключить "новых"'),
		'in_work'=>t('исключить "в работе"'),
		'reserv'=>t('исключить резерв'),
		'archive'=>t('исключить архив'),
		'active'=>t('только активные'),
		'public'=>t('только публичные'),
	    );
	    return ($id) ? (isset($ret[$id]) ? $ret[$id] : array() ) : $ret;
		    
	}
	
	public static function parse_filters($filters=array()) {
	    $filters = unserialize($filters);
	    
	    $ret = array('empty'=>array('type'=>'не выбран','values'=>array()));
	    if(!empty($filters)) {
		
		$ret = array();
		foreach ($filters as $key => $value) {
		    $data = unserialize($filters[$key]);
		    switch($key) {
			case 'status':
			    $ret[$key]['type'] = 'Статус';
			    foreach ($data as $status_id) {
				$type = floor($status_id/10);
				$ret[$key]['values'][$status_id] = profile_peer::get_status($type, $status_id);
			    }
			    break;
			case 'location':
			    $ret[$key]['type'] = 'Местонахождение';
			    foreach ($data as $location_type=>$location_value) 
				switch($location_type) {
				    case 'country':
					$ret[$key]['values'][$location_value] = geo_peer::instance()->get_country($location_value);
					break;
				    case 'region':
					$ret[$key]['values'][$location_value] = geo_peer::instance()->get_region($location_value);
					break;
				    case 'city':
					$ret[$key]['values'][$location_value] = geo_peer::instance()->get_city($location_value);
					break;
				    case 'another_city':
					$ret[$key]['values'][$location_value] = $location_value;
					break;
				}
			    break;
			case 'agency':
			    load::model('agency');
			    $ret[$key]['type'] = 'Агенство';
			    foreach ($data as $agency_id) 
				$ret[$key]['values'][$agency_id] = agency_peer::get_agency ($agency_id);
			    break;
			case 'extended':
			    $ret[$key]['type'] = 'Расширенные';
			    foreach ($data as $ex_type => $ex_val) 
				if($ex_val)
				    $ret[$key]['values'][$ex_type] = mailing_peer::get_extended_filter($ex_type);
			    break;
		    }
		}
	    }
	    return ($ret);
	}
	
	
	public static function prepare_list($filters) {
	    
	    $filter_keys = array_keys(mailing_peer::get_filter());
	
	    $sql = "SELECT a.id FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE a.email<>''";
	    $sqladd = '';

	    $bind = array();

	    $filters = unserialize($filters);

	    foreach ($filter_keys as $filter) {

		switch($filter) {
		    case 'status':
			$list = unserialize ($filters[$filter]);
			if(is_array($list)) {
			    $sqladd .= ' AND d.status IN ('.implode(',',$list).')';
			}
			break;
		    case 'agency':
			$list = unserialize ($filters[$filter]);
			if(is_array($list)) {
			    $sqladd .= ' AND a.id IN (SELECT user_id FROM user_agency WHERE agency_id IN ('.implode(',',$list).'))';
			}
			break;
		    case 'location':
			$locations = unserialize($filters[$filter]);

			if($locations['country']) {
			    $sqladd .= ' AND d.country=:country';
			    $bind['country'] = $locations['country'];
			}
			if($locations['region']) {
			    $sqladd .= ' AND d.region=:region';
			    $bind['region'] = $locations['region'];
			}

			if($locations['city']) {
			    $sqladd .= ' AND d.city=:city';
			    $bind['city'] = $locations['city'];
			}

			if($locations['another_city']) {
			    $sqladd .= ' AND d.another_city=:another_city';
			    $bind['another_city'] = $locations['another_city'];
			}
			break;
		    case 'extended':
			$extended = unserialize($filters[$filter]);
			$sqladd .= ($extended['archive']) ? ' AND a.del=0' : '';
			$sqladd .= ($extended['not_approved']) ? ' AND ((a.registrator=0 AND a.approve>0) OR a.registrator>0)' : '';
			$sqladd .= ($extended['in_work']) ? ' AND ((a.registrator=0 AND a.approve>1)  OR a.registrator>0)' : '';
			$sqladd .= ($extended['reserv']) ? ' AND a.reserv=0' : '';
			$sqladd .= ($extended['active']) ? ' AND a.active=true' : '';
			$sqladd .= ($extended['public']) ? ' AND a.hidden=false' : '';
			break;
		}
	    }
	    $list = db::get_cols($sql.$sqladd.' ORDER BY a.id ASC', $bind);
	    return $list;
	    
	}
}
?>
