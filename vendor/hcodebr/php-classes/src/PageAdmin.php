<?php

namespace Hcode;

class PageAdmin extends Page {

	public function __construct($opts = array(), $tpl_dir = "/views/admin/")
	{
		parent::__construct($opts, $tpl_dir);//aqui o método da classe pai (Page) está sendo chamado já que ele irá funcionar da mesma maneira

	}
}

?>