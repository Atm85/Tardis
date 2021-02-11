<?php


namespace Atom\tardis\managers;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use xenialdan\customui\elements\Button;
use xenialdan\customui\windows\SimpleForm;

class WarpCommand extends BaseCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare() : void {
        $this->registerArgument(0, new RawStringArgument("name", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {

        $manager = $this->plugin->getWarpManager();

        if (!$sender instanceof Player) {
            return;
        }

        if (!isset($args["name"])) {
            $form = new SimpleForm("Warps");
            array_map(function ($warp) use ($form) {
                $form->addButton(new Button($warp->getName()));
            }, $manager->getAll());

            $form->setCallable(function (Player $player, string $warpName) {
                $this->plugin->getServer()->dispatchCommand($player, "warp $warpName");
            });

            $sender->sendForm($form);
            return;
        }

        $warp = $manager->getWarp($args["name"]);
        if ($warp == null) {
            $sender->sendMessage("'".$args["name"]."' does not exist");
            return;
        }

        $sender->sendMessage("warping to '".$args["name"]."'");
        $sender->teleport($warp->getPosition());
    }

}
