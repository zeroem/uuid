<?php

namespace Zeroem\UUID;

interface UUIDGeneratorInterface
{
  /**
   * Generate a UUID
   * @return string
   */
  function generate();
}