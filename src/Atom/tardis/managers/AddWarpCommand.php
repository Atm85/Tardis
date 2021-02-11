<?php


namespace Atom\tardis\managers;


use Atom\tardis\Tardis;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class AddWarpCommand extends BaseCommand {

    /** @var Tardis */
    protected $plugin;

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare() : void {
        $this->registerArgument(0, new RawStringArgument("name", true));
        $this->setPermission("tardis.addwarp");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {

        if (!$sender instanceof Player) {
            return;
        }

        if (!$sender->hasPermission("tardis.addwarp")) {
            $sender->sendMessage("You do not have permission to create warps");
            return;
        }

        if (!isset($args["name"])) {
            $sender->sendMessage("Usage: /addwarp <name>");
            return;
        }

        $manager = $this->plugin->getWarpManager();
        $success = $manager->saveWarp(new Warp($args["name"], $sender->getPosition()));
        if ($success) {
            $sender->sendMessage("successfully created warp '".$args["name"]."'");
        } else {
            $sender->sendMessage("warp '".$args["name"]."' already exists...");
        }
    }
}
