<?php

namespace PluginInfo\Backend;

interface BackendInterface
{
    public function getPluginJson();
    public function getInfo();
    public function getDescription();
    public function setPlugin($plugin);
}