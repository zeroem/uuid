<?php

namspace Zeroem\UUID;

class UUIDGenerator implements UUIDGeneratorInterface
{
  private $node;

  public function __construct($node) {
    $this->node = $node;
  }

  public function generate() {
    $uuid = new UUID($this->node);

    return "{$uuid}";
  }
}