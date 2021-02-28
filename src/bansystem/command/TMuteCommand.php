<?php

namespace bansystem\command;

use bansystem\Manager;
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

class TMuteCommand extends Command {
    
    public function __construct() {
        parent::__construct("tmute");
        $this->description = "Temporarily prevents the player from sending chat message.";
        $this->usageMessage = "/tmute <player> <timeFormat> [reason...]";
        $this->setPermission("bansystem.command.tempmute");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $webHook = new Webhook("https://discord.com/api/webhooks/815613927907131403/nspwpciJLJQB5ItQpzvDCKDSF7ywde6s_0XixfuXhOE_4xMdckVBVHFuTUNLH1El-BF9");
        $embed = new Embed();
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 1) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $muteList = Manager::getNameMutes();
            $player = $sender->getServer()->getPlayer($args[0]);
            try {
                $expiry = new Countdown($args[1]);
                $expiryToString = Countdown::expirationTimerToString($expiry->getDate(), new DateTime());
                if ($muteList->isBanned($args[0])) {
                    $sender->sendMessage(Translation::translate("playerAlreadyMuted"));
                    return false;
                }
                if (count($args) == 2) {
                    if ($player != null) {
                        $muteList->addBan($player->getName(), null, $expiry->getDate(), $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been temporarily muted from our network!\n§4Muted by: §bStaff\n§6until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $player->sendMessage(TextFormat::RED . "You have been temporarily muted from our network!\n§4Muted by: §bStaff\n§6until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $embed->setTitle("Temporary Mute");
                        $embed->setDescription($player->getName() . " has been temporarily muted until" . $expiryToString);
                        $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                        $msg->addEmbed($embed);
                        $webHook->send($msg);
                    } else {
                        $muteList->addBan($args[0], null, $expiry->getDate(), $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " has been temporarily muted from our network!\n§4Muted by: §bStaff\n§6until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $embed->setTitle("Temporary Mute");
                        $embed->setDescription($args[0] . " has been temporarily muted until" . $expiryToString);
                        $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                        $msg->addEmbed($embed);
                        $webHook->send($msg);
                    }
                } else if (count($args) >= 3) {
                    $reason = "";
                    for ($i = 2; $i < count($args); $i++) {
                        $reason .= $args[$i];
                        $reason .= " ";
                    }
                    $reason = substr($reason, 0, strlen($reason) - 1);
                    if ($player != null) {
                        $muteList->addBan($player->getName(), $reason, $expiry->getDate(), $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been temporarily muted from our network!\n§4Muted by: §bStaff\n§5Reason: " . TextFormat::AQUA . $reason . " §6until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $player->sendMessage(TextFormat::RED . "You have been temporarily muted from our network!\n§4Muted by: §bStaff\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . " §6until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $embed->setTitle("Temporary Mute");
                        $embed->setDescription($playerName . " has been temporarily muted until" . $expiryToString . " reason: " . $reason);
                        $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                        $msg->addEmbed($embed);
                        $webHook->send($msg);
                    } else {
                        $muteList->addBan($args[0], $reason, $expiry->getDate(), $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " has been temporarily muted from our network!\n§4Muted by: §bStaff\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . " §6until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $embed->setTitle("Temporary Mute");
                        $embed->setDescription($args[0] . " has been temporarily muted until" . $expiryToString . " reason: " . $reason);
                        $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
                        $msg->addEmbed($embed);
                        $webHook->send($msg);
                    }
                }
            } catch (InvalidArgumentException $ex) {
                $sender->sendMessage(TextFormat::RED . $ex->getMessage());
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}
