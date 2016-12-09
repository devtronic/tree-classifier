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

use Devtronic\TreeClassifier\Node;
use Devtronic\TreeClassifier\SimpleNode;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testConstruct()
    {
        $node = new SimpleNode();

        $this->assertTrue($node instanceof Node);
    }

    public function testSampleAdding()
    {
        $samples = range(0, 21);

        $node = new SimpleNode();
        $node->addSubjects($samples);

        $this->assertEquals($samples, $node->getSubjects());

        $node->addSubject(22);
        $this->assertEquals(23, count($node->getSubjects()));
    }

    public function testSubNodeAdding()
    {
        $node = new SimpleNode();
        $subNode = new SimpleNode();
        $subNodes = [new SimpleNode(), new SimpleNode()];

        $node->addSubNode($subNode);
        $node->addSubNodes($subNodes);

        $this->assertSame($subNode, $node->getSubNode(0));
        $this->assertSame($subNodes[0], $node->getSubNode(1));
        $this->assertEquals(3, count($node->getSubNodes()));
    }

    public function testClassify()
    {
        $node = new SimpleNode();
        $this->assertTrue($node->classify());
    }
}