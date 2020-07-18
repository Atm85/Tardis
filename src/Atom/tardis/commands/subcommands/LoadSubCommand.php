<?php


namespace Atom\tardis\commands\subcommands;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class LoadSubCommand extends TardisSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
        $this->setPermission("tardis.command.load");
        $this->registerArgument(0, new RawStringArgument("world", false));
    }

    public function onExecute(Player $sender, array $args): void {

        $worldName = $args["world"];
        if (!in_array($worldName, $this->plugin->getAll())) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis&r&c - World '$worldName' does not exist"));
            return;
        }

        if ($this->plugin->getServer()->isLevelLoaded($worldName)) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis&r&c - '$worldName' is already loaded"));
            return;
        }

        $this->plugin->getServer()->loadLevel($worldName);
        $sender->sendMessage(TextFormat::colorize("&l&9Tardis&r&b - World '$worldName' loaded"));

    }

}
