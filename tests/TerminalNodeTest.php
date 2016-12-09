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

use Devtronic\TreeClassifier\RootNode;
use Devtronic\TreeClassifier\TerminalNode;
use PHPUnit\Framework\TestCase;

class TerminalNodeTest extends TestCase
{
    public function testGetResult()
    {
        $samples = range(0, 21);
        $rootNode = new RootNode($samples);

        $terminalNode = new TerminalNode();
        $rootNode->addSubNode($terminalNode);

        $rootNode->classify();

        $this->assertSame($samples, $terminalNode->getResult());
    }
}