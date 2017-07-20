<?php

namespace CCMS\Core;

class ModulesManager
{
    private $storedData = [];

    private $modules = [];

    public function registerModule($moduleName, $moduleInfo){
        $this->modules[$moduleName] = $moduleInfo;
    }

    public function storeDataForModule($moduleName, $type, $name, $value){
        $this->storedData[$moduleName][$type][$name] = $value;
    }

    /**
     * @param string $moduleName
     * @return array
     */
    public function getStoredDataForModule($moduleName){
        if(!isset($this->storedData[$moduleName])){
            return [];
        }
        return $this->storedData[$moduleName];
    }
}