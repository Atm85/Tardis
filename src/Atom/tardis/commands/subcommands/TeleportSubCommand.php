<?php


namespace Atom\tardis\commands\subcommands;


use Atom\tardis\Tardis;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TeleportSubCommand extends TardisSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
        $this->setPermission("tardis.command.tp");
        $this->registerArgument(0, new RawStringArgument("worldName", true));
    }

    public function onExecute(Player $sender, array $args): void {

        if (!isset($args["worldName"])) {
            $sender->sendForm(Tardis::$staticUI);
            return;
        }

        $worldName = $args["worldName"];
        $level = $this->plugin->getServer()->getLevelByName($worldName);
        if ($level === null) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&c - World '$worldName' does not exist"));
            return;
        }

        $sender->teleport($level->getSafeSpawn());
        $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&b - Teleporting to '$worldName'"));

    }

}
