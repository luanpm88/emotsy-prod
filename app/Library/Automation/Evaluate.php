<?php

namespace Acelle\Library\Automation;

class Evaluate implements ActionInterface
{
    protected $id;
    protected $title;
    protected $childYes;
    protected $childNo;
    protected $options = [];

    protected $next = null;

    function __construct($params = [])
    {
        $this->id = $params['id'];
        $this->title = $params['title'];
        $this->options = $params['options'];
        $this->childYes = $params['childYes'];
        $this->childNo = $params['childNo'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getOptions() {
        return $this->options;
    }

    public function toJson()
    {
        return [
            'title' => $this->getTitle(),
            'id' => $this->getId(),
            'childYes' => $this->childYes,
            'childNo' => $this->childNo,
            'options' => $this->getOptions()
        ];
    }

    public function execute($callback)
    {
        if ($this->check() == true) {
            $this->next = $this->childYes;
        } else {
            $this->next = $this->childNo;
        }

        return true; // Send and Evalue always return true, Wait may return false
    }

    public function getNextActionId()
    {
        return $this->next;
    }
}
