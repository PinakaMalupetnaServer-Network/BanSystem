<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class BanCommand extends Command {
    
    public function __construct() {
        parent::__construct("ban");
        $this->description = "Prevents an given player to use this server.";
        $this->usageMessage = "/ban <player> [reason...]";
        $this->setPermission("bansystem.command.ban");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
      $webHook = new Webhook("YOUR WEBHOOK URL");
      $embed = new Embed();
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            $banList = $sender->getServer()->getNameBans();
            $playerName = $args[0];
            if ($banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playerAlreadyBanned"));
                return false;
            }
            if (count($args) == 1) {
                if ($player != null) {
                    $banList->addBan($player->getName(), null, null, $sender->getName());
                    $player->kick(TextFormat::RED . "Your account has been suspended from our network!\n§4Banned by: §bStaff", false);
                    $playerName = $player->getName();
                } else {
                    $banList->addBan($args[0], null, null, $sender->getName());
                }
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . $playerName . TextFormat::RED . " has been suspended from our network!\n§4Banned by: §bStaff");
                $embed->setTitle("Banned");
                $embed->setDescription($playerName . " has been banned to our Network!");
                $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                $msg->addEmbed($embed);
                $webHook->send($msg);
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($player != null) {
                    $banList->addBan($player->getName(), $reason, null, $sender->getName());
                    $player->kick(TextFormat::RED . "You have been suspended from our network!\n§4Banned by: §bStaff\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".", false);
                    $playerName = $player->getName();
                } else {
                    $banList->addBan($args[0], $reason, null, $sender->getName());
                }
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . $playerName . TextFormat::RED . " has been suspended from our network!\n§4Banned by: §bStaff\n§5Reason: "
                        . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                $embed->setTitle("Banned");
                $embed->setDescription($playerName . " has been banned to our Network for " . $reason);
                $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                $msg->addEmbed($embed);
                $webHook->send($msg);
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}
