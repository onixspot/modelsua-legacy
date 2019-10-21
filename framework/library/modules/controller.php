<?

abstract class kernel_controller extends basic_controller
{
	public function get_template_path()
	{
		return getenv('FRAMEWORK_PATH')
			."/library/modules/"
			.str_replace("_", "/modules/", $this->get_module())
			."/views/".$this->template.".view.php";
	}
}
