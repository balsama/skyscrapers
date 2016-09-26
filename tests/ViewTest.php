<?php

use Balsama\Skyscrapers\View;

class ViewTest extends PHPUnit_Framework_TestCase {

  public function testViewSetGetBlocks() {
    $view = new View();
    $set_blocks = 5;
    $view->setBlocks($set_blocks);
    $get_blocks = $view->getBlocks();
    $this->assertTrue($set_blocks == $get_blocks);
  }

  public function testGetPermutations() {
    $view = new View();
    $this->assertTrue(count($view->getPermutations()) == 6);

    $view->setBlocks(2);
    $this->assertTrue(count($view->getPermutations()) == 2);
  }

  public function testGetVisible() {
    $view = new View();
    $shouldBeThree = $view->getVisible([1,2,3]);
    $this->assertTrue($shouldBeThree === 3);

    $shouldBeTwo = $view->getVisible([2,3,1]);
    $this->assertTrue($shouldBeTwo === 2);

    $shouldBeOne = $view->getVisible([3,1,2]);
    $this->assertTrue($shouldBeOne === 1);
  }

  public function testGetValidPermutations() {
    $view = new View();
    $this->assertTrue(count($view->getValidPermutations()) === 1);

    $view->setDesiredVisible(2);
    $this->assertTrue(count($view->getValidPermutations()) === 3);
  }

  public function testConstraints() {
    $view = new View();

    // Constrained permutation list is same as unconstrained list if no
    // constraints are set.
    $this->assertTrue($view->getValidPermutations() == $view->getValidConstrainedPermutations());

    // No possible combinations show three if first building is 2
    $view->setConstraint(1, "==", 2);
    $this->assertTrue(empty($view->getValidConstrainedPermutations()));

    // No possible combinations show three if first building greater than 1
    $view->setConstraint(1, ">", 1);
    $this->assertTrue(empty($view->getValidConstrainedPermutations()));

    $view->setDesiredVisible(2);
    $view->setConstraint(1, "<", 2);
    $this->assertTrue(count($view->getValidConstrainedPermutations()) == 1);
  }
}