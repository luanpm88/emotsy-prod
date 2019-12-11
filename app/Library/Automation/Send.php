<?php

namespace Acelle\Library\Automation;

class Send implements ActionInterface
{
    protected $id;
    protected $title;
    protected $child;

    function __construct($params = [])
    {
        //title: "Send a Welcome email", id: "welcome", type: "ElementAction", child: "condition"
        $this->id = $params['id'];
        $this->title = $params['title'];
        $this->child = $params['child'];
        $this->email_id = $params['email_id'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getEmailId() {
        return $this->email_id;
    }

    public function getOptions()
    {
        return []; // No options needed for Send
    }

    public function toJson()
    {
        return [
            'title' => $this->getTitle(),
            'id' => $this->getId(),
            'child' => $this->child,
            'email_id' => $this->email_id
        ];
    }

    public function execute($callback)
    {
        $callback($this); // send message job, raise exception in case of failure
        return true; // Send and Evalue always return true, Wait may return false
    }

    public function getNextActionId()
    {
        return $this->child;
    }
}
