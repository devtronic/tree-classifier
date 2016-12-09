<?php
/*
 * This file is part of the Devtronic Tree Classifier package.
 *
 * (c) Julian Finkler <admin@developer-heaven.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtronic\TreeClassifier;

class DecisionNode extends Node
{
    /** @var \Closure */
    protected $deciderCallback;

    public function __construct(\Closure $deciderCallback, array $decisions)
    {
        $this->deciderCallback = $deciderCallback;
        $this->subNodes = $decisions;
    }

    public function classify()
    {
        $fn = &$this->deciderCallback;
        foreach ($this->subjects as &$subject) {
            $decision = $fn($subject);
            if (isset($this->subNodes[$decision])) {
                $this->subNodes[$decision]->addSubject($subject);
            }
        }
        /** @var Node $subNode */
        foreach ($this->subNodes as &$subNode) {
            $subNode->classify();
        }
    }
}
