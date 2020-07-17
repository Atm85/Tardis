<?php


namespace Atom\worldcore\commands;


use Atom\worldcore\commands\subcommands\FixnameSubCommand;
use Atom\worldcore\commands\subcommands\RenameSubCommand;
use Atom\worldcore\WorldCore;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class WorldCoreCommand extends BaseCommand {

    /** @var WorldCore */
    protected $plugin;

    public function __construct(WorldCore $plugin, string $name, string $description = "", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
        $this->plugin = $plugin;
    }

    protected function prepare(): void {
        $this->setPermission("worldcore.command");
        $this->registerSubCommand(new FixnameSubCommand($this->plugin, "fixname", "fix world names"));
        $this->registerSubCommand(new RenameSubCommand($this->plugin, "rename", "rename a world"));
    }

    public function onRun(CommandSender $sender, string $command, array $args): void {
        $sender->sendMessage(TextFormat::colorize("&l&6WorldCore"));
        $sender->sendMessage(TextFormat::colorize("&e /$command load [world] &f- load level"));
        $sender->sendMessage(TextFormat::colorize("&e /$command unload [world] &f- unload level"));
        $sender->sendMessage(TextFormat::colorize("&e /$command list [world] &f- lists all worlds on the server"));
        $sender->sendMessage(TextFormat::colorize("&e /$command teleport [world] &f- teleport to a world"));
        $sender->sendMessage(TextFormat::colorize("&e /$command fixname [all:world] &f- fix world names"));
        $sender->sendMessage(TextFormat::colorize("&e /$command rename [world] &f- rename a world"));
    }

}
