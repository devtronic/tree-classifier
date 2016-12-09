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
use PHPUnit\Framework\TestCase;

class RootNodeTest extends TestCase
{
    public function testConstruct()
    {
        $samples = range(0, 21);
        $rootNode = new RootNode($samples);

        $this->assertSame($samples, $rootNode->getSubjects());
    }
}