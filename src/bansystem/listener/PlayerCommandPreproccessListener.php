<?php

namespace bansystem\listener;

use bansystem\Manager;
use bansystem\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\TextFormat;

class PlayerCommandPreproccessListener implements Listener {
    
    public function onPlayerCommandPreproccess(PlayerCommandPreprocessEvent $event) {
        $player = $event->getPlayer();
        $blockList = Manager::getNameBlocks();
        $str = str_split($event->getMessage());
        if ($str[0] != "/") {
            return;
        }
        if ($blockList->isBanned($player->getName())) {
            $blockMessage = "";
            $entries = $blockList->getEntries();
            $entry = $entries[strtolower($player->getName())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $blockMessage = TextFormat::RED . "You're currently blocked for " . TextFormat::WHITE . $reason . TextFormat::RED . ". If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                } else {
                    $blockMessage = TextFormat::RED . "You're currently blocked. If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $blockList->remove($entry->getName());
                    return;
                }
                $blockReason = $entry->getReason();
                if ($blockReason != null || $blockReason != "") {
                    $blockReason = TextFormat::RED . "You're currently blocked for " . TextFormat::WHITE . $blockReason . TextFormat::RED . " until " . TextFormat::WHITE . $expiry . TextFormat::RED . ". If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                } else {
                    $blockReason = TextFormat::RED . "You're currently blocked until " . TextFormat::WHITE . $expiry . TextFormat::RED . ". If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($blockMessage);
        }
    }
    
    public function onPlayerCommandPreproccess2(PlayerCommandPreprocessEvent $event) {
        $player = $event->getPlayer();
        $blockList = Manager::getIPBlocks();
        $str = str_split($event->getMessage());
        if ($str[0] != "/") {
            return;
        }
        if ($blockList->isBanned($player->getAddress())) {
            $blockMessage = "";
            $entries = $blockList->getEntries();
            $entry = $entries[strtolower($player->getAddress())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $blockMessage = TextFormat::RED . "You're currently IP blocked for " . TextFormat::WHITE . $reason . TextFormat::RED . ". If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                } else {
                    $blockMessage = TextFormat::RED . "You're currently IP blocked. If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $blockList->remove($entry->getName());
                    return;
                }
                $blockReason = $entry->getReason();
                if ($blockReason != null || $blockReason != "") {
                    $blockReason = TextFormat::RED . "You're currently IP blocked for " . TextFormat::WHITE . $blockReason . TextFormat::RED . " until " . TextFormat::WHITE . $expiry . TextFormat::RED . ". If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                } else {
                    $blockReason = TextFormat::RED . "You're currently IP blocked until " . TextFormat::WHITE . $expiry . TextFormat::RED . ". If this is a mistake please open a ticket at https://bit.ly/thepmns.";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($blockMessage);
        }
    }
}
