<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class PardonCommand extends Command {
    
    public function __construct() {
        parent::__construct("pardon");
        $this->description = "Allows the given players to use this server.";
        $this->usageMessage = "/pardon <player>";
        $this->setPermission("bansystem.command.pardon");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $webHook = new Webhook("https://discord.com/api/webhooks/815613927907131403/nspwpciJLJQB5ItQpzvDCKDSF7ywde6s_0XixfuXhOE_4xMdckVBVHFuTUNLH1El-BF9");
        $msg = new Message();
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
            $embed->setTitle("Unbanned");
            $embed->setColor(0x008000);
            $embed->setDescription($args[0] . " has been unbanned on this network!");
            $embed->setFooter("BanSystem for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
            $msg->addEmbed($embed);
            $webHook->send($msg);
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}
