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

abstract class Node
{
    /** @var array */
    protected $subjects = [];

    /** @var Node[] */
    protected $subNodes = [];

    public abstract function classify();

    public function addSubject($subject)
    {
        $this->subjects[] = &$subject;
        return $this;
    }

    public function addSubjects($subjects)
    {
        foreach ($subjects as &$subject) {
            $this->subjects[] = &$subject;
        }
        return $this;
    }

    public function &getSubjects()
    {
        return $this->subjects;
    }

    public function &getSubject($index)
    {
        return $this->subjects[$index];
    }

    public function addSubNode(Node &$subNode)
    {
        $this->subNodes[] = &$subNode;
        return $this;
    }

    public function addSubNodes(array &$subNodes)
    {
        foreach ($subNodes as &$subNode) {
            $this->subNodes[] = &$subNode;
        }
        return $this;
    }

    public function &getSubNode($index)
    {
        return $this->subNodes[$index];
    }

    public function &getSubNodes()
    {
        return $this->subNodes;
    }

}