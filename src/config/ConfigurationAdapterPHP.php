<?php
class ConfigurationAdapterPHP extends Base implements ConfigurationAdapter {
    public function convert($input) {
        if(is_array($input)) {
            $config = $input;
        } else if(is_string($input) && file_exists($input)) {
            $config = include($input);
        } else {
            return false;
        }

        return $config;
    }
}
?>
