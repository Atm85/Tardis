<?php


namespace Atom\tardis\commands;


use Atom\tardis\commands\subcommands\FixnameSubCommand;
use Atom\tardis\commands\subcommands\ListSubCommand;
use Atom\tardis\commands\subcommands\LoadSubCommand;
use Atom\tardis\commands\subcommands\RenameSubCommand;
use Atom\tardis\commands\subcommands\TeleportSubCommand;
use Atom\tardis\commands\subcommands\UnloadSubCommand;
use Atom\tardis\Tardis;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class TardisCommand extends BaseCommand {

    /** @var Tardis */
    protected $plugin;

    public function __construct(Tardis $plugin, string $name, string $description = "", array $aliases = []) {
        parent::__construct($plugin, $name, $description, $aliases);
        $this->plugin = $plugin;
    }

    protected function prepare(): void {
        $this->setPermission("tardis.command");
        $this->registerSubCommand(new LoadSubCommand($this->plugin, "load", "load world"));
        $this->registerSubCommand(new UnloadSubCommand($this->plugin, "unload", "unload world"));
        $this->registerSubCommand(new ListSubCommand($this->plugin, "list", "lists all world on the server"));
        $this->registerSubCommand(new TeleportSubCommand($this->plugin, "teleport", "teleport to a world", ["tp"]));
        $this->registerSubCommand(new FixnameSubCommand($this->plugin, "fixname", "fix world names"));
        $this->registerSubCommand(new RenameSubCommand($this->plugin, "rename", "rename a world"));
    }

    public function onRun(CommandSender $sender, string $command, array $args): void {
        $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r- world manager"));
        $sender->sendMessage(TextFormat::colorize("&b /$command load [world] &f- load world"));
        $sender->sendMessage(TextFormat::colorize("&b /$command unload [world] &f- unload world"));
        $sender->sendMessage(TextFormat::colorize("&b /$command list [world] &f- lists all worlds on the server"));
        $sender->sendMessage(TextFormat::colorize("&b /$command teleport [world] &f- teleport to a world"));
        $sender->sendMessage(TextFormat::colorize("&b /$command fixname [all:world] &f- fix world names"));
        $sender->sendMessage(TextFormat::colorize("&b /$command rename [world] &f- rename a world"));
    }

}
