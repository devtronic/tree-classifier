[![GitHub tag](https://img.shields.io/packagist/v/devtronic/tree-classifier.svg)](https://github.com/Devtronic/tree-classifier)
[![Packagist](https://img.shields.io/packagist/l/devtronic/tree-classifier.svg)](https://github.com/Devtronic/tree-classifier/blob/master/LICENSE)
[![Travis](https://img.shields.io/travis/Devtronic/tree-classifier.svg)](https://travis-ci.org/Devtronic/tree-classifier/)
[![Packagist](https://img.shields.io/packagist/dt/devtronic/tree-classifier.svg)](https://github.com/Devtronic/tree-classifier)


# Tree Classifier
A PHP Library for decision trees

### What is a decision tree?
This (really basic): ![TreeClassifier](http://www.developer-heaven.de/wp-content/uploads/2016/12/TreeClassifier.png)

### Code Example
In this example we classify 10 Persons. We want to find all males under 50 years old who can cook and don't playing football
```php
<?php

use Devtronic\TreeClassifier\DecisionNode;
use Devtronic\TreeClassifier\RootNode;
use Devtronic\TreeClassifier\TerminalNode;

require_once 'vendor/autoload.php';

$subjects = [
    0 => ['gender' => 'male',   'age' => 41, 'playsFootball' => 'nope', 'canCook' => 'nope'],
    1 => ['gender' => 'female', 'age' => 91, 'playsFootball' => 'nope', 'canCook' => 'nope'],
    2 => ['gender' => 'male',   'age' => 17, 'playsFootball' => 'nope', 'canCook' => 'yes'],
    3 => ['gender' => 'female', 'age' => 39, 'playsFootball' => 'nope', 'canCook' => 'yes'],
    4 => ['gender' => 'male',   'age' => 90, 'playsFootball' => 'nope', 'canCook' => 'yes'],
    5 => ['gender' => 'male',   'age' => 51, 'playsFootball' => 'nope', 'canCook' => 'yes'],
    6 => ['gender' => 'male',   'age' => 86, 'playsFootball' => 'yes',  'canCook' => 'nope'],
    7 => ['gender' => 'male',   'age' => 99, 'playsFootball' => 'yes',  'canCook' => 'yes'],
    8 => ['gender' => 'female', 'age' => 39, 'playsFootball' => 'nope', 'canCook' => 'yes'],
    9 => ['gender' => 'female', 'age' => 37, 'playsFootball' => 'yes',  'canCook' => 'yes'],
];

// Find all
// - males
// - under 50
// - can cook
// - does not play football

// Create from bottom up

// We want all subjects who don't play football
$footballDecisions = [
    'play' => new TerminalNode(),
    'does not play' => new TerminalNode(), // This is our last node
];

// Create the decider for football
$footballDecider = new DecisionNode(function ($subject) {
    // This is our decider function, $subject is the current object
    // in the queue of the current node.
    // Return the key of our $footballDecision-Array
    return ($subject['playsFootball'] == 'yes' ? 'play' : 'does not play');
}, $footballDecisions);

// Great, next we need the cook-decisions.
$cookDecisions = [
    'can cook' => $footballDecider, // redirect all subjects who can cook to the $footballDecider
    'can not cook' => new TerminalNode(),
];

// Now the cookDecider
$cookDecider = new DecisionNode(function ($subject) {
    return ($subject['canCook'] == 'yes' ? 'can cook' : 'can not cook');
}, $cookDecisions);

// The same as previous for the next 2 decisions

$ageDecisions = [
    '< 50' => $cookDecider,
    '>= 50' => new TerminalNode(),
];
$ageDecider = new DecisionNode(function ($subject) {
    return ($subject['age'] >= 50 ? '>= 50' : '< 50');
}, $ageDecisions);

$genderDecisions = [
    'male' => $ageDecider,
    'female' => new TerminalNode(),
];
$genderDecider = new DecisionNode(function ($subject) {
    return $subject['gender'];
}, $genderDecisions);

// And now we need to create a RootNode
$rootNode = new RootNode($subjects);

// Add the first (last created) node to our RootNode:
$rootNode->addSubNode($genderDecider);

// And classify
$rootNode->classify();

// In $footballDecisions['does not play'] are our subjects there we looked for:
print_r($footballDecisions['does not play']);

// Outputs
// Array
// (
//     [0] => Array
//         (
//             [gender] => male
//             [age] => 17
//             [playsFootball] => nope
//             [canCook] => yes
//         )
//   )


// Explanation:
//                 [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] = Keys from $subjects-Array
//                 \_________ RootNode _________/
//                                |
//                 /------ Gender Decider ------\
//                 |                            |
//               Female                        Male
//            [1, 3, 8, 9]             [0, 2, 4, 5, 6, 7]
//                 |                            |
//           Terminal Node                      |
//                                  /-----  Age Decider -----\
//                                  |                        |
//                               >= 50                      < 50
//                            [4, 5, 6, 7]                 [0, 2]
//                                  |                        |
//                            Terminal Node                  |
//                                                /---- Cook Decider ----\
//                                                |                      |
//                                           Can not cook            Can cook
//                                               [0]                    [2]
//                                                |                      |
//                                           Terminal Node               |
//                                                           /---- Football Decider ----\
//                                                           |                          |
//                                                         play                   does not play
//                                                          [ ]                        [2]
//                                                           |                          |
//                                                     Terminal Node              Terminal Node

```