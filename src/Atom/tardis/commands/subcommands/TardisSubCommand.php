<?php


namespace Atom\tardis\commands\subcommands;


use Atom\tardis\Tardis;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TardisSubCommand extends BaseSubCommand {

    protected $plugin;

    public function __construct(Tardis $plugin, string $name, string $description = "", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
        $this->plugin = $plugin;
    }

    protected function prepare(): void {
    }

    public function onRun(CommandSender $sender, string $command, array $args): void {

        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&cIn-game usage only!"));
            return;
        }

        $this->onExecute($sender, $args);

    }

    public function onExecute(Player $sender, array $args): void {
    }

}
