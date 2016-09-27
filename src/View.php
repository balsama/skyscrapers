<?php

namespace Balsama\Skyscrapers;
use SebastianBergmann\Exporter\Exception;
use NajiDev\Permutation;

/**
 * Class View
 * @package Balsama\Skyscrapers
 *
 * Provides methods to help determine valid order of Skyscrapers given the
 * number of Skyscraper Buildings (blocks) and the desired number of those which
 * are visible.
 */
class View {

  /**
   * The number of blocks to the furthest skyscraper from the view.
   *
   * @var int
   */
  private $blocks = 3;

  /**
   * Sets the number of blocks.
   *
   * @param int $blocks
   *   The number of blocks to the furthest skyscraper from the view.
   * @throws \Exception
   */
  public function setBlocks($blocks) {
    if ($blocks > 5) {
      throw new \Exception("We would need more power for that.");
    }
    $this->blocks = $blocks;
  }

  /**
   * Returns the number of blocks from the view.
   *
   * @return int
   *   The number of blocks to the furthest skyscraper from the view.
   */
  public function getBlocks() {
    return $this->blocks;
  }

  /**
   * Gets all the possible permutations of skyscrapers given the block length.
   */
  public function getPermutations() {
    $blocks = $this->getBlocks();

    // Convert the number of blocks into the height of buildings within it
    // (given that only one building of each height can exist in a row or
    // column).
    $buildings = $this->integerToArray($blocks);
    $permutations = [];

    $iterations = new Permutation\PermutationIterator($buildings);
    foreach ($iterations as $iteration) {
      $permutations[] = $iteration;
    }

    return $permutations;
  }

  /**
   * The desired number of buildings visible from a view.
   *
   * @var int
   */
  private $desiredVisible = 3;

  /**
   * Sets the desired number of visible buildings.
   *
   * @param int $desiredVisible
   *    The number of desired visible buildings
   * @throws \Exception
   */
  public function setDesiredVisible($desiredVisible) {
    if ($desiredVisible > $this->getBlocks()) {
      throw new \Exception("Desired visible cannot be larger than total number of blocks");
    }
    $this->desiredVisible = $desiredVisible;
  }

  /**
   * Gets the number of desired visible buildings
   *
   * @return int
   */
  public function getDesiredVisible() {
    return $this->desiredVisible;
  }

  /**
   * Returns all valid permutations.
   *
   * Given the number of Skyscrapers (block length) and the desired number
   * visible, returns all possible orders of those skyscrapers.
   *
   * @return array
   *   The valid permutations
   */
  public function getValidPermutations() {
    $desiredVisible = $this->getDesiredVisible();
    $allPermutations = $this->getPermutations();
    $validPermutations = [];
    foreach ($allPermutations as $possibleValidPermutation) {
      if ($this->getVisible($possibleValidPermutation) == $desiredVisible) {
        $validPermutations[] = $possibleValidPermutation;
      }
    }
    return $validPermutations;
  }

  /**
   * Given an array of building heights, gets the number that are visible.
   */
  public function getVisible($orderedScrapers) {
    $prevScraper = 0;
    $visibleCount = 0;
    foreach($orderedScrapers as $scraper) {
      if ($scraper > $prevScraper) {
        $visibleCount++;
        $prevScraper = $scraper;
      }
    }
    return $visibleCount;
  }

  /**
   * @var null
   *   Stores any constraints that have been set on possible building heights.
   */
  private $constraint = [];

  /**
   * Sets a constraint.
   *
   * @param int $position
   *   The position of the building (1 is the closest to you)
   * @param string $operator
   *   The operator for the function. Allowed values:
   *   - < (less then)
   *   - > (greater then)
   *   - == (equals)
   * @param int $value
   *   The value for the operator to act on.
   * @throws \Exception
   */
  public function setConstraint($position, $operator, $value) {
    if ($position > $this->getBlocks()) {
      throw new \Exception('Position must be less than total blocks.');
    }
    $position--;
    if (!in_array($operator, ['>', '<', '=='])) {
      throw new Exception('Operator must be one of \'>\', \'<\', \'==\'');
    }
    if ((!is_int($value)) || ($value < 1)) {
      throw new Exception('Value must be a positive integer');
    }
    $this->constraint = [
      'position' => $position,
      'operator' => $operator,
      'value' => $value,
    ];
  }

  /**
   * Gets constraint if defined.
   *
   * @return
   *   The current constraint
   */
  public function getConstraint() {
    return $this->constraint;
  }

  public function getValidConstrainedPermutations() {
    $validPermutations = $this->getValidPermutations();
    $constraint = $this->getConstraint();
    if (!$constraint) {
      return $validPermutations;
    }
    $validConstrainedPermutations = [];
    foreach ($validPermutations as $validPermutation) {
      switch($constraint['operator']) {
        case ">":
          if ($validPermutation[$constraint['position']] > $constraint['value']) {
            $validConstrainedPermutations[] = $validPermutation;
          }
          break;
        case "<":
          if ($validPermutation[$constraint['position']] < $constraint['value']) {
            $validConstrainedPermutations[] = $validPermutation;
          }
          break;
        case "==":
          if ($validPermutation[$constraint['position']] == $constraint['value']) {
            $validConstrainedPermutations[] = $validPermutation;
          }
      }
    }
    return $validConstrainedPermutations;
  }

  /**
   * Converts an integer into an array of integers that come before it.
   *
   * @param $int
   *   The integer to array-ize
   * @return array
   */
  private function integerToArray($int) {
    $depth = [];
    $i = $int;
    while ($i > 0) {
      $depth[] = $i;
      $i--;
    }
    return $depth;
  }

}
