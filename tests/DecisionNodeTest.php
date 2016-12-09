<?php
/*
 * This file is part of the Devtronic Tree Classifier package.
 *
 * (c) Julian Finkler <admin@developer-heaven.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtronic\Tests\TreeClassifier;

use Devtronic\TreeClassifier\DecisionNode;
use Devtronic\TreeClassifier\RootNode;
use Devtronic\TreeClassifier\SimpleNode;
use PHPUnit\Framework\TestCase;

class DecisionNodeTest extends TestCase
{
    public function testSimpleOddEvenDecision()
    {
        $samples = range(0, 21);
        $rootNode = new RootNode($samples);

        $expected_odd = range(1, 21, 2);
        $expected_even = range(0, 21, 2);

        $decisions = [
            'odd' => new SimpleNode(),
            'even' => new SimpleNode()
        ];
        $decisionNode = new DecisionNode(function ($subject) {
            return $subject % 2 === 0 ? 'even' : 'odd';
        }, $decisions);

        $rootNode->addSubNode($decisionNode);

        $rootNode->classify();

        $this->assertSame($expected_odd, $decisions['odd']->getSubjects());
        $this->assertSame($expected_even, $decisions['even']->getSubjects());
    }

    public function testNestedOddEvenPrimeDecision()
    {
        $samples = range(0, 21);
        $rootNode = new RootNode($samples);

        $expected_odd = range(1, 21, 2);
        $expected_even = range(0, 21, 2);
        $expected_primes = [1, 3, 5, 7, 11, 13, 17, 19];

        $primeDecisions = [
            'odd' => new SimpleNode(),
            'prime' => new SimpleNode(),
        ];
        $primeDecisionNode = new DecisionNode(function ($subject) {
            for ($i = 2; $i < $subject; $i++) {
                if ($subject % $i === 0) {
                    return 'odd';
                }
            }
            return 'prime';
        }, $primeDecisions);

        $decisions = [
            'odd' => $primeDecisionNode,
            'even' => new SimpleNode()
        ];
        $decisionNode = new DecisionNode(function ($subject) {
            return $subject % 2 === 0 ? 'even' : 'odd';
        }, $decisions);

        $rootNode->addSubNode($decisionNode);

        $rootNode->classify();

        $this->assertSame($expected_odd, $decisions['odd']->getSubjects());
        $this->assertSame($expected_even, $decisions['even']->getSubjects());
        $this->assertSame($expected_primes, $primeDecisions['prime']->getSubjects());
    }
}