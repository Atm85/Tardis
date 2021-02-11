<?php


namespace Atom\tardis\managers;


use pocketmine\level\Position;

class Warp {

    /** @var string */
    private $name;

    /** @var Position */
    private $position;

    public function __construct(string $name, Position $position) {
        $this->name = $name;
        $this->position = $position;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPosition() : Position {
        return $this->position;
    }
}
