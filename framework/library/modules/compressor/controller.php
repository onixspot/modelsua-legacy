<?php

abstract class compressor_controller extends basic_controller
{
	
	private $cache = "/data/cache";
	private $cache_time = 3600;
	private $tmp = "/data/temp";
	private $sources = array(
			"js" => array(
					"content-type" => "application/x-javascript",
					"path" => "/public/js",
			),
			"css" => array(
					"content-type" => "text/css",
					"path" => "/public/css",
			),
			"img" => array(
					"content-type" => "&image.mime;",
					"path" => "/public/img",
			),
	);
	private $compressor = "./binaries/compressor.jar";
	private $type;
	private $file;
	
	public $groups = array();
	
	public function initialize()
	{
		$this->set_renderer(null);
		
		$this->compressor = dirname(__FILE__).DIRECTORY_SEPARATOR.$this->compressor;
		
		$host = context::get("host");
		$host = preg_split("(\.)", $host);
		
		$this->type = $host[0];
		
		$this->file = request::get("file", "");
	}
	
	public function add_file($group_name, $file_name)
	{
		$fpath = conf::get("project_root").$this->sources[$this->type]["path"]
						.DIRECTORY_SEPARATOR.$file_name;
		if(!is_file($fpath))
		{
			return false;
		}
		
		if( ! is_array($this->groups[$group_name]))
		{
			$this->add_group($group_name);
		}
		
		$this->groups[$group_name][] = $file_name;
	}
	
	public function add_group($group_name)
	{
		if(is_array($this->groups[$group_name]))
		{
			return false;
		}
		
		$this->groups[$group_name] = array();
	}
	
	public function add_files($array)
	{
		foreach($array as $group_name => $files)
			foreach($files as $file_name)
			{
				$this->add_file($group_name, $file_name);
			}
	}
	
	public function set_cache_time($time)
	{
		$this->cache_time = $time;
	}
	
	public function get_cache_time()
	{
		return $this->cache_time;
	}
	
	public function display()
	{
		if( ! in_array($this->file, array_keys($this->groups)))
		{
			$fpath = conf::get("project_root").$this->sources[$this->type]["path"]
							.DIRECTORY_SEPARATOR.$this->file;
			if( ! is_file($fpath))
				return false;
		}
		else
		{
			if( ! ($fpath = $this->pack()))
				return false;
		}
		
		$verify_data = array(
				"source" => $this->sources[$this->type]["content-type"],
				"file_name" => $fpath
		);
		
		$this->sources[$this->type]["content-type"] = $this->verify($verify_data);
		
		$this->set_header("Content-type:", $this->sources[$this->type]["content-type"]);
		
		if($this->type != "img")
		{
			$fpath = $this->compress($fpath, $this->type);
		}
		
		if((int) $fpath > 0)
			return false;
		
		echo $this->read_file($fpath);
	}
	
	/*
	 * Private functions
	 */
	private function read_file($fpath)
	{
		if( ! ($handle = fopen($fpath, "r")))
			return false;
		
		$fbuffer = fread($handle, filesize($fpath));
		fclose($handle);
		
		return $fbuffer;
	}
	
	private function build_name($file_name, $post_fix = "compressed")
	{
		$tokens = preg_split("(\.)", $file_name);
		$ext = $tokens[count($tokens)-1];
		unset($tokens[count($tokens)-1]);
		array_push($tokens, $post_fix, $ext);
		$file_name = implode(".", $tokens);
		return $file_name;
	}
	
	/*
	 * If successful compressing return path to compressed file 
	 * else return number of error
	 */
	private function compress($fpath, $type)
	{
		$tokens = preg_split("(\/)", $fpath);
		
		$file_name = $this->build_name($tokens[count($tokens)-1]);
		$ofpath = conf::get("project_root").$this->cache.DIRECTORY_SEPARATOR.$file_name;
		if(file_exists($ofpath))
		{
			$ftime = filemtime($ofpath);
			if($ftime + $this->cache_time > time())
				return $ofpath;
		}
		
		$cmd = "java -jar ".$this->compressor." ".$fpath." -o ".$ofpath." --type ".$type." --charset utf-8";
		exec($cmd, $output, $return_val);
		
		if($return_val)
			return $return_val;
		
		return $ofpath;
	}
	
	/*
	 * If successful packing return path to packed files
	 * else return false
	 */
	private function pack()
	{
		$file_name = $this->build_name($this->file, "packed");
		$ofpath = conf::get("project_root").$this->tmp.DIRECTORY_SEPARATOR.$file_name;
		
		if(is_file($ofpath))
		{
			unlink($ofpath);
		}
		
		if($handle = fopen($ofpath, "w"))
		{
			foreach($this->groups[$this->file] as $file)
			{
				$fpath = conf::get("project_root").$this->sources[$this->type]["path"]
							.DIRECTORY_SEPARATOR.$file;
				
				$file_content = $this->read_file($fpath)."\n\n";
				
				$verify_data = array(
						"source" => $file_content,
						"file_name" => $fpath
				);

				$file_content = $this->verify($verify_data);
				
				fwrite($handle, $file_content);
			}
			fclose($handle);
		}
		else
			return false;
		
		return $ofpath;
	}
	
	/*
	 * In source must include string like &conf.server;
	 * Usage:
	 *		Input data: Array("source" => $source, "file_name" => $file_name)
	 *		Output data: Compiled source;
	 */
	private function verify($data = array())
	{
		
		$tokens = preg_split("(\&|;)", $data["source"]);
		foreach($tokens as $token)
		{
			@list($object, $property) = explode(".", $token);
			if(in_array($object, array("image", "conf")))
			{
				$inp_data = array(
						"property" => $property,
						"file_name" => $data["file_name"]
				);
				$value = $this->$object($inp_data);
				$data["source"]= str_replace("&".$token.";", $value, $data["source"]);
			}
		}
		return $data["source"];
	}
	
	private function image($data = array())
	{
		$img = @getimagesize($data["file_name"]);
		if( ! $img)
			return false;
		
		$img["width"] = $img[0];
		$img["height"] = $img[1];
		
		return $img[$data["property"]];
	}
	
	private function conf($data = array())
	{
		return conf::get($data["property"]);
	}
	
}

?>
