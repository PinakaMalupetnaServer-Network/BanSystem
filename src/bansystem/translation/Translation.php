<?php

namespace bansystem\translation;

use bansystem\exception\TranslationFailedException;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class Translation {
    
    public static function translate(string $translation) : string {
        switch ($translation) {
            case "noPermission":
                return TextFormat::RED . "You don't have permission to use this! ask your admin for access.";
            case "playerNotFound":
                return TextFormat::GREEN . "Player is not online.";
            case "playerAlreadyBanned":
                return TextFormat::GREEN . "Player is already banned.";
            case "ipAlreadyBanned":
                return TextFormat::GREEN . "Player is already IP banned.";
            case "ipNotBanned":
                return TextFormat::GREEN . "IP address is not banned.";
            case "ipAlreadyMuted":
                return TextFormat::GREEN . "IP address is already muted.";
            case "playerNotBanned":
                return TextFormat::GREEN . "Player is not banned.";
            case "playerAlreadyMuted":
                return TextFormat::GREEN . "Player is already muted.";
            case "playerNotMuted":
                return TextFormat::GREEN . "Player is not muted.";
            case "ipNotMuted":
                return TextFormat::GREEN . "IP address is not muted.";
            case "playerAlreadyBlocked":
                return TextFormat::GREEN . "Player is already blocked.";
            case "playerNotBlocked":
                return TextFormat::GREEN . "Player is not blocked.";
            case "ipAlreadyBlocked":
                return TextFormat::GREEN . "IP address is already blocked.";
            case "ipNotBlocked":
                return TextFormat::GREEN . "IP address is not blocked.";
            default:
                throw new TranslationFailedException("Failed to translate.");
        }
    }
    
    public static function translateParams(string $translation, array $parameters) : string {
        if (empty($parameters)) {
            throw new InvalidArgumentException("Parameter is empty.");
        }
        switch ($translation) {
            case "usage":
                $command = $parameters[0];
                if ($command instanceof Command) {
                    return TextFormat::YELLOW . "Usage: " . TextFormat::GREEN . $command->getUsage();
                } else {
                    throw new InvalidArgumentException("Parameter index 0 must be the type of Command.");
                }
        }
    }
}
