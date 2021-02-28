<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class PardonIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("pardon-ip");
        $this->description = "Allows the given IP address to use this server.";
        $this->usageMessage = "/pardon-ip <address>";
        $this->setPermission("bansystem.command.pardonip");
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
            if (!$banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("ipNotBanned"));
                return false;
            }
            $banList->remove($args[0]);
            $sender->getServer()->broadcastMessage(TextFormat::GREEN . "Address " . TextFormat::AQUA . $args[0] . TextFormat::GREEN . " has been unbanned.");
            $embed->setTitle("IP Unbanned);
            $embed->setDescription("someone has been unbanned on this network!);
            $embed->setFooter("AdvancedBan for PMnS","https://cdn.discordapp.com/attachments/784812448535674889/815586272180830248/pmnsoldlogo.jpg");
            $msg->addEmbed($embed);
            $webHook->send($msg);
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}
