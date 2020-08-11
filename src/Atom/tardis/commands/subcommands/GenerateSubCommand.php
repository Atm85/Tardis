<?php


namespace Atom\tardis\commands\subcommands;


use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\level\generator\normal\Normal;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GenerateSubCommand extends TardisSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
        $this->setPermission("tardis.command.generate");
        $this->registerArgument(0, new RawStringArgument("name", false));
        $this->registerArgument(1, new IntegerArgument("seed", false));
        $this->registerArgument(2, new RawStringArgument("generator", false));
    }

    public function onExecute(Player $sender, array $args): void {

        $name = $args["name"];
        $seed = $args["seed"];
        $generatorName = $args["generator"];

        if (in_array($name, $this->plugin->getAll())) {
            $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&c - World '$name' already exists"));
            return;
        }

        $generator = [];
        switch ($generatorName) {
            case strtolower("normal"):
            default:
                $generator = [Normal::class, "normal"];
                break;
        }

        $this->plugin->getServer()->generateLevel($name, $seed, $generator[0]);
        $sender->sendMessage(TextFormat::colorize("&l&9Tardis &r&b - '&e$name&b' was generated using generator '&e$generator[1]&b' with seed '&e$seed&b'"));
    }
}
