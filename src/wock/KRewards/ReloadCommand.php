<?php

namespace wock\KRewards;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ReloadCommand extends Command {

    public function __construct()
    {
        parent::__construct("killrewards", "Kill rewards commands", "/killrewards reload", ["kr"]);
        $this->setPermission("kill_rewards.reload");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender->hasPermission("kill_rewards.reload")) {
            $sender->sendMessage("You cannot execute this command.");
            return false;
        }

        $config = KillRewards::getInstance()->getConfig();
        $config->reload();
        $sender->sendMessage("Successfully reloaded the config.");
        return true;
    }
}