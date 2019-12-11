<?php

namespace Acelle\Library\Automation;

class Wait implements ActionInterface
{
    protected $id;
    protected $title;
    protected $child;
    protected $options = [];

    function __construct($params = [])
    {
        //title: "Send a Welcome email", id: "welcome", type: "ElementAction", child: "condition"
        $this->id = $params['id'];
        $this->title = $params['title'];
        $this->child = $params['child'];
        $this->options = $params['options'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function toJson()
    {
        return [
            'title' => $this->getTitle(),
            'id' => $this->getId(),
            'child' => $this->child,
            'options' => $this->getOptions()
        ];
    }

    public function execute($callback)
    {
        // evaluate time condition
        if (1 == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getNextActionId()
    {
        return $this->child;
    }
}
