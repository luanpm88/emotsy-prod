<?php

namespace Acelle\Library\Automation;

abstract class Action
{
    protected $id;
    protected $title;
    protected $type;
    protected $latest = false;
    protected $child;
    protected $options;

    public function __construct($params = [])
    {
        $this->id = $params['id'];
        $this->title = $params['title'];
        $this->type = $params['type'];
        $this->child = array_key_exists('child', $params) ? $params['child'] : null;
        $this->options = array_key_exists('options', $params) ? $params['options'] : [];
        $this->latest = array_key_exists('latest', $params) ? $params['latest'] : null;
    }

    public function toJson()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'type' => $this->type,
            'child' => $this->child,
            'latest' => $this->isLatest(),
            'options' => $this->getOptions(),
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function getNextActionId()
    {
        return $this->child;
    }

    public function isLatest()
    {
        return $this->latest;
    }

    public function markAsLatest($latest = true)
    {
        $this->latest = $latest;
    }
}
