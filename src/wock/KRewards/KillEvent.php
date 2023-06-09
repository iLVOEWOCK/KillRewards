<?php

namespace wock\KRewards;

use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;

class KillEvent implements Listener{

    public function onPlayerDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();

        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();

            if ($damager instanceof Player) {
                $this->sendMoneyToKiller($damager);
            }
        }
    }

    public function sendMoneyToKiller(Player $killer): void {
        $minAmount = KillRewards::getInstance()->getConfig()->getNested("min_amount", 100);
        $maxAmount = KillRewards::getInstance()->getConfig()->getNested("max_amount", 1000);
        $amount = mt_rand($minAmount, $maxAmount);
        $economyPlugin = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
        if ($economyPlugin !== null) {
            EconomyAPI::getInstance()->addMoney($killer, $amount);
            $message = KillRewards::getInstance()->getConfig()->getNested("message", "You received $" . number_format($amount) . " for killing a player!");
            $message = str_replace("{amount}", number_format($amount), $message);
            $message = TextFormat::colorize($message);

            $killer->sendMessage($message);
        } else {
            Server::getInstance()->getLogger()->warning("Economy plugin not found. Unable to send money to killer.");
        }
    }
}