<?php

namespace tests;

use tests\BaseTest;
use application\controller\HomeController;

final class HomeControllerTest extends BaseTest{

	public function testIndex() {

		$controller = new HomeController();
		
		$output = $this->request("GET", $controller, "action_index");
		$expected = "<h2>My first framework</h2>";
		
		$this->assertContains($expected, $output);
	}	

}
