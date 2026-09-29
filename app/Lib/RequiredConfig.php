<?php

namespace App\Lib;

class RequiredConfig{

    public function getConfig(){
        return [
            'general_setting' => [
                'title'=>'Configure basic setting of your site like Site Name, Currency, Timezone etc',
                'route'=>route('admin.settings.index')
            ],
            'logo_favicon' => [
                'title'=>'Update the logo and favicon',
                'route'=>route('admin.settings.index')
            ],
        ];
    }

    public function totalConfigs(){
        return count($this->getConfig());
    }

    public function completedConfig(){
        return gs('config_progress') ?? [];
    }

    public function completedConfigCount(){
        return count($this->completedConfig() ?? []);
    }

    public function completedConfigPercent(){
        return ($this->completedConfigCount() / $this->totalConfigs()) * 100;
    }

    public static function configured($key){
        $completedConfig = gs('config_progress') ?? [];
        if (!in_array($key,$completedConfig)) {
            $general = gs();
            $general->config_progress = array_merge($completedConfig,[$key]);
            $general->save();
        }
    }
}
