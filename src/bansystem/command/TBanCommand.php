<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use bansystem\util\date\Countdown;
use DateTime;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class TBanCommand extends Command {
    
    public function __construct() {
        parent::__construct("tban");
        $this->description = "Temporarily prevents an given player from using this server.";
        $this->usageMessage = "/tban <player> <timeFormat> [reason...]";
        $this->setPermission("bansystem.command.tempban");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $webHook = new Webhook("https://discord.com/api/webhooks/815613927907131403/nspwpciJLJQB5ItQpzvDCKDSF7ywde6s_0XixfuXhOE_4xMdckVBVHFuTUNLH1El-BF9");
        $embed = new Embed();
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 1) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            $playerName = $args[0]; 
            $banList = $sender->getServer()->getNameBans();
            try {
                if ($banList->isBanned($args[0])) {
                    $sender->sendMessage(Translation::translate("playerAlreadyBanned"));
                    return false;
                }
                $expiry = new Countdown($args[1]);
                $expiryToString = Countdown::expirationTimerToString($expiry->getDate(), new DateTime());
                if (count($args) == 2) {
                    if ($player != null) {
                        $playerName = $player->getName();
                        $banList->addBan($player->getName(), null, $expiry->getDate(), $sender->getName());
                        $player->kick(TextFormat::RED . "You have been temporarily suspended from our network\n§4Banned by: §bStaff"
                                . " §6your ban expires in " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".", false);
                    } else {
                        $banList->addBan($args[0], null, $expiry->getDate(), $sender->getName());
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $playerName
                            . TextFormat::RED . " has been temporarily banned from our network\n§4Banned by: §bStaff §6Banned until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                    $embed->setTitle("Temporary Ban");
                    $embed->setDescription($playerName . " has been temporarily banned until" . $expiryToString);
                    $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                    $msg->addEmbed($embed);
                    $webHook->send($msg);
                } else if (count($args) >= 3) {
                    $banReason = "";
                    for ($i = 2; $i < count($args); $i++) {
                        $banReason .= $args[$i];
                        $banReason .= " ";
                    }
                    $banReason = substr($banReason, 0, strlen($banReason) - 1);
                    if ($player != null) {
                        $banList->addBan($player->getName(), $banReason, $expiry->getDate(), $sender->getName());
                        $player->kick(TextFormat::RED . "You have been temporarily banned from our network!\n§4Banned by: §bStaff\n§5Reason: " . TextFormat::AQUA . $banReason . TextFormat::RED . ","
                                . " §6Your ban expires in " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".", false);
                    } else {
                        $banList->addBan($args[0], $banReason, $expiry->getDate(), $sender->getName());
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $playerName
                            . TextFormat::RED . " has been temporarily banned from our network\n§4Banned by: §bStaff\n§5Reason: " . TextFormat::AQUA . $banReason . TextFormat::RED . " §6Your ban expires in " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                    $embed->setTitle("Temporary Ban");
                    $embed->setDescription($playerName . " has been temporarily banned until" . $expiryToString . " reason: " . $banReason);
                    $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                    $msg->addEmbed($embed);
                    $webHook->send($msg);
                }
            } catch (InvalidArgumentException $e) {
                $sender->sendMessage(TextFormat::RED . $e->getMessage());
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}
