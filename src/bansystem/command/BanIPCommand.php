<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class BanIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("ban-ip");
        $this->description = "Prevents the given IP address to use this server.";
        $this->usageMessage  = "/ban-ip <player> <address> [reason...]";
        $this->setPermission("bansystem.command.banip");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $webHook = new Webhook("https://discord.com/api/webhooks/815613927907131403/nspwpciJLJQB5ItQpzvDCKDSF7ywde6s_0XixfuXhOE_4xMdckVBVHFuTUNLH1El-BF9");
        $embed = new Embed();
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $banList = $sender->getServer()->getIPBans();
            if ($banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("ipAlreadyBanned"));
                return false;
            }
            $ip = filter_var($args[0], FILTER_VALIDATE_IP);
            $player = $sender->getServer()->getPlayer($args[0]);
            if (count($args) == 1) {
                if ($ip != null) {
                    $banList->addBan($ip, null, null, $sender->getName());
                    foreach ($sender->getServer()->getOnlinePlayers() as $onlinePlayers) {
                        if ($onlinePlayers->getAddress() == $ip) {
                            $onlinePlayers->kick(TextFormat::RED . "You have been IP banned from our network!\n§4Banned by: §bStaff\n§5with no reason.", false);
                        }
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::RED . "Address " . TextFormat::AQUA . $ip . TextFormat::RED . " has been IP banned from our network\n§4Banned by: §bStaff\n§5with no reason.");
                    $embed->setTitle("IP Banned");
                    $embed->setDescription("someone has been  IP banned to our Network!");
                    $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                    $msg->addEmbed($embed);
                    $webHook->send($msg);
                } else {
                    if ($player != null) {
                        $banList->addBan($player->getAddress(), null, null, $sender->getName());
                        $player->kick(TextFormat::RED . "You have been IP banned from our network\n§4Banned by: §bStaff\n§5with no reason.", false);
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been IP banned from our network\n§4Banned by: §bStaff\n§5with no reason.");
                        $sender->getServer()->broadcastMessage(TextFormat::RED . "Address " . TextFormat::AQUA . $ip . TextFormat::RED . " has been IP banned from our network\n§4Banned by: §bStaff\n§5with no reason.");
                        $embed->setTitle("IP Banned");
                        $embed->setDescription($player->getName() . " has been  IP banned to our Network!");
                        $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                        $msg->addEmbed($embed);
                        $webHook->send($msg);
                    } else {
                        $sender->sendMessage(Translation::translate("playerNotFound"));
                    }
                }
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($ip != null) {
                    $sender->getServer()->getIPBans()->addBan($ip, $reason, null, $sender->getName());
                    foreach ($sender->getServer()->getOnlinePlayers() as $players) {
                        if ($players->getAddress() == $ip) {
                            $players->kick(TextFormat::RED . "You have been IP banned from our network!\n§4Banned by: §bStaff\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".", false);
                        }
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::RED . "Address " . TextFormat::AQUA . $ip . TextFormat::RED . " has been IP banned from our network\n§4Banned by: §bStaff\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                    $embed->setTitle("IP Banned");
                    $embed->setDescription("someone has been  IP banned to our Network!");
                    $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                    $msg->addEmbed($embed);
                    $webHook->send($msg);
                } else {
                    if ($player != null) {
                        $banList->addBan($player->getAddress(), $reason, null, $sender->getName());
                        $player->kick(TextFormat::RED . "You have been IP banned from §4PMnS §eNetwork! §5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".", false);
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been IP banned from our network\n§4Banned by: §bStaff\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".");  
                    $embed->setTitle("IP Banned");
                    $embed->setDescription($player->getName() . " has been  IP banned to our Network!");
                    $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                    $msg->addEmbed($embed);
                    $webHook->send($msg);
                    } else {
                        $sender->sendMessage(Translation::translate("playerNotFound"));
                    }
                }
            } else {
                $sender->sendMessage(Translation::translate("noPermission"));
            }
        }
        return true;
    }
}
