<?php


namespace Atom\tardis\managers;


use Atom\tardis\Tardis;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class DelWarpCommand extends BaseCommand {

    /** @var Tardis */
    protected $plugin;

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare() : void {
        $this->registerArgument(0, new RawStringArgument("name", true));
        $this->setPermission("tardis.delwarp");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {

        if (!$sender instanceof Player) {
            return;
        }

        if (!$sender->hasPermission("tardis.delwarp")) {
            $sender->sendMessage("You do not have permission to create warps");
            return;
        }

        if (!isset($args["name"])) {
            $sender->sendMessage("Usage: /delwarp <name>");
            return;
        }

        $manager = $this->plugin->getWarpManager();
        $warp = $manager->getWarp($args["name"]);
        if ($warp == null) {
            $sender->sendMessage("warp '".$args["name"]."' does not exists...");
            return;
        }

        $success = $manager->delete($warp);
        if ($success) {
            $sender->sendMessage("successfully deleted warp '".$args["name"]."'");
        } else {
            $sender->sendMessage("warp '".$args["name"]."' does not exists...");
        }
    }
}
