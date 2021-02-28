<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class UnbanCommand extends Command {
    
    public function __construct() {
        parent::__construct("unban");
        $this->description = "Allows the given player to use this server.";
        $this->usageMessage = "/unban <player>";
        $this->setPermission("bansystem.command.unban");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $webHook = new Webhook("YOUR WEBHOOK URL");
        $embed = new Embed();
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $banList = $sender->getServer()->getNameBans();
            if (!$banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playerNotBanned"));
                return false;
            }
            $banList->remove($args[0]);
            $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::GREEN . " has been unbanned.");
            $embed->setTitle("Unbanned);
            $embed->setDescription($args[0] . " has been unbanned on this network!);
            $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
            $msg->addEmbed($embed);
            $webHook->send($msg);
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}
