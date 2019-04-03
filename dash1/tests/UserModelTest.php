<?php

use tests\BaseTest;
use application\model\UserModel;

final class UserModelTest extends BaseTest
{

	public function testUserIsExisted(){
		$userModel = new UserModel();
		$user = $userModel->getUserById(1);
		$this->assertEquals(array(), $user); 
		$this->assertNotEquals(null, $user);
	 }

	 public function testUserDelete(){
		$userModel = new UserModel();
		$userModel->deleteUserById(1);

		$user = $userModel->getUserById(1);
		$this->assertEquals(array(), $user); 
		$this->assertNotEquals(null, $user);
	 }	 
	 /**
	  * @dataProvider providerCategory
	  */
	 public function testCategories($a) {
		$this->assertEquals(1, $a[0]);
	 }

	 public function providerCategory() {
		 return array(
			 1,
			 2,
			 3
		 );
	 }

}