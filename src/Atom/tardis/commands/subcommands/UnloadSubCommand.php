<?php


namespace Atom\tardis\commands\subcommands;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class UnloadSubCommand extends TardisSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
        $this->setPermission("tardis.command.unload");
        $this->registerArgument(0, new RawStringArgument("world", false));
    }

    public function onExecute(Player $sender, array $args): void {

        $worldName = $args["world"];
        $level = $this->plugin->getServer()->getLevelByName($worldName);
        if (!in_array($worldName, $this->plugin->getAll())) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis&r&c - World '$worldName' does not exist"));
            return;
        }

        if (!$this->plugin->getServer()->isLevelLoaded($worldName)) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis&r&c - '$worldName' is already unloaded"));
            return;
        }

        $this->plugin->getServer()->unloadLevel($level);
        $sender->sendMessage(TextFormat::colorize("&l&9Tardis&r&b - World '$worldName' unloaded"));

    }

}
