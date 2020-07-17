<?php


namespace Atom\tardis\commands\subcommands;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class RenameSubCommand extends WorldCoreSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void {
        $this->setPermission("tardis.command.rename");
        $this->registerArgument(0, new RawStringArgument("oldname", false));
        $this->registerArgument(1, new RawStringArgument("newname", false));
    }

    public function onExecute(Player $sender, array $args): void {

        $oldname = $args["oldname"];
        $newname = $args["newname"];

        if ($sender->getLevel()->getName() === $oldname) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&c - You cannot rename the level you're currently in!"));
            return;
        }

        $level = $this->plugin->getServer()->getLevelByName($oldname);
        if ($level === null) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&c - level '$oldname' could not be found!"));
            return;
        }

        if (count($level->getPlayers()) > 0) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&c - You cannot rename the level when there are players in it!"));
            return;
        }

        $this->plugin->renameWorld($level, $newname);
        $sender->sendMessage(TextFormat::colorize("&l&9Tardis&r&b - You renamed '$oldname' to '$newname'"));

    }

}
