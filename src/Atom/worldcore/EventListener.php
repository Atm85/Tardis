<?php


namespace Atom\worldcore;


use pocketmine\event\level\LevelLoadEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class EventListener implements Listener {

    /** @var WorldCore */
    private $plugin;

    public function __construct(WorldCore $plugin) {
        $this->plugin = $plugin;
    }

    public function onLevelLoad(LevelLoadEvent $event): void {
        if (!$this->plugin->config->get("loadall-on-startup")) {
            $this->plugin->fixWorld($event->getLevel());
        }
    }

}
