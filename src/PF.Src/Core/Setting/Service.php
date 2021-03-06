<?php

namespace Core\Setting;

class Service extends \Core\Model
{
    private $_app;

    public function __construct(\Core\App\Object $App)
    {
        parent::__construct();

        $this->_app = $App;
    }

    /**
     * @deprecated from 4.7.0
     *
     * @param $settings
     *
     * @return bool
     */
    public function save($settings)
    {
        foreach ($settings as $key => $value) {
            if (isset($this->_app->settings->{$key}->requires) && $value == '1') {
                $set_value = (isset($settings[$this->_app->settings->{$key}->requires]) ? $settings[$this->_app->settings->{$key}->requires] : false);
                if (!$set_value) {
                    error(_p('"{{ name }}" requires setting "{{ requires }}".',
                        ['name' => $this->_app->settings->{$key}->info, 'requires' => $this->_app->settings->{$this->_app->settings->{$key}->requires}->info]));
                }
            }
        }

        $file = PHPFOX_DIR_SETTINGS . md5($this->_app->id . '-settings') . '.php';
        file_put_contents($file, "<?php\nreturn " . var_export($settings, true) . ";");

        foreach ($settings as $key => $value) {
            $this->db->delete(':setting', ['var_name' => $key]);
            $this->db->insert(':setting', [
                'module_id'       => 'app_' . $this->_app->id,
                'product_id'      => $this->_app->id,
                'is_hidden'       => 1,
                'type_id'         => 'string',
                'var_name'        => $key,
                'phrase_var_name' => $key,
                'value_actual'    => $value,
                'value_default'   => $value,
            ]);
        }

        $this->cache->del('app_settings');

        return true;
    }
}