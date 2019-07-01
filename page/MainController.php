<?php

namespace page;

use core\BaseModel;

class MainController
{
	public function indexAction()
	{
		require_once 'public/main/index.php';

		$average = BaseModel::averageAllCount();
		echo '<br><p style="color:red">В среднем вы тратите : '.$average.'</p>';
	}
}