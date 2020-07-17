<?php


namespace Atom\tardis\commands\subcommands;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class FixnameSubCommand extends WorldCoreSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void {
        $this->setPermission("tardis.command.fixname");
        $this->registerArgument(0, new RawStringArgument("[all:world]", false));
    }

    public function onExecute(Player $sender, array $args): void {

        if ($args["[all:world]"] === "all") {
            $sender->sendMessage(TextFormat::GREEN."Fixed ".$this->plugin->fixAll()." world name(s)!");
            return;
        }

        if ($this->plugin->getServer()->loadLevel($args["[all:world]"])) {

            $level = $this->plugin->getServer()->getLevelByName($args["[all:world]"]);
            if ($this->plugin->fixWorld($level)) {
                $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&b - world name '".$args["[all:world]"]."' has been fixed"));
            } else {
                $sender->sendMessage(TextFormat::colorize("&l&4Tardis &r&c - world '".$args["[all:world]"]."' is already fixed"));
            }

        } else {
            $sender->sendMessage(TextFormat::colorize("&l&4Tardis &r&c - That world with name '".$args["[all:world]"]."' could not be found..."));
        }

    }

}
