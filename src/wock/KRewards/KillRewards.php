<?php

namespace wock\KRewards;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class KillRewards extends PluginBase {

    /** @var KillRewards */
    private static $instance;

    public Config $config;

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->registerEvents();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

    }

    public function registerEvents() {
        $pluginManager = $this->getServer()->getPluginManager();
        $pluginManager->registerEvents(new KillEvent(), $this);
    }

    public static function getInstance(): KillRewards
    {
        return self::$instance;
    }
}
