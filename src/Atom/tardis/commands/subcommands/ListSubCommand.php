<?php


namespace Atom\tardis\commands\subcommands;


use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ListSubCommand extends TardisSubCommand {

    protected function prepare(): void {
        $this->setPermission("tardis.command.list");
    }

    public function onExecute(Player $sender, array $args): void {

        $folders = scandir($this->plugin->path);
        $server = $this->plugin->getServer();
        $worldList = "";
        $playerCount = 0;
        for ($i = 2; $i<count($folders); $i++) {
            $name = $folders[$i];
            $formatting = $server->isLevelLoaded($name) ? TextFormat::GREEN." Loaded" : TextFormat::RED." Unloaded";
            if (($level = $server->getLevelByName($name)) != null) $playerCount = count($level->getPlayers());
            $worldList .= $formatting.TextFormat::colorize(" &r> &b$name&r: &e$playerCount&b players\n");
        }

        $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r- world manager"));
        $sender->sendMessage($worldList);

    }

}
