[![Build Status](https://travis-ci.org/balsama/skyscrapers.svg?branch=1.0.x)](https://travis-ci.org/acquia/lightning)

# Skyscrapers
A php class to help you solve the Skyscrapers puzzles from NY Times Magazine.

## Usage

### Basic

    $view = new View();
    
    // There are four buildings per view, for a total of 24 purmutations.
    $view->setBlocks(4);
    
    // Only show permutations in which exactly three Skyscrapers are visible.
    $view->setDesiredVisible(3);
    
    // Only show permutuations where the fourth Skyscraper is of height 4
    $view->addConstraint(4, "==", 4);
    
    // Also, only show permutations where the second is greater than 1
    $view->addConstraint(2, ">", 1);
    
    // And the first is not 2
    $view->addConstraint(1, "!=", 2);
    
    // Get the solutions
    $possibleSolutions = $view->getSolutions();

### Description
A View is what one would see if standing at the end of a row or column on the
puzzle-board. Instatiate a View:

    $view = new View();

The number of total Skyscrapers in a view is set by the blocks() method. By
default, there are three blocks in a view. The puzzles in NYTimes have been
either 4 or 5 blocks long. To change the number of blocks:

    $view->setBlocks($number_of_blocks);

The NYTimes puzzle provides the number of Skyscrapers visible for certain views.
To set that number for the view, you can use the setDesiredVisible() method. By
default it is 3. To change the desired number visible:

    $view->setDesiredVisible($number_visible);

As you solve the puzzle, you'll come across situations where you know that
certain blocks will hold a skyscraper of a certain height (or below or above a
certain height). Constraints can be chained - that is, you can define more than
one constraint without affecting previously defined constraints. To make the
class aware of these, you can add a constraint. The addConstraint() method takes
three arguments:

- Position: The position of the block in the view (1 is closest).
- Operator: The relationship of the building to the value (available operators:
    - greater than (">")
    - less than ("<")
    - equals ("==")
- Value: The value to use for comparison.

The following constrains the results to those which have a Skyscraper in the
third block with a height of three:

    $view->setConstraint(3, "==", 3);

Once you have the blocks, desired visible, and any constraints set, you can get
the results with:

    $view->getSolutions();

