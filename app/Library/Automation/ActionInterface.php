<?php

namespace Acelle\Library\Automation;

interface ActionInterface
{
    public function execute($callback);
    public function toJson();
    public function getId();
    public function getTitle();
    public function getOptions();
    public function getNextActionId();
}