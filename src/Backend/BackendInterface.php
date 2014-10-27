<?php

namespace PluginInfo\Backend;

interface BackendInterface
{
    public function getPluginInfo();
    public function getInfo();
    public function getDescription();
    public function setPlugin($plugin);
}