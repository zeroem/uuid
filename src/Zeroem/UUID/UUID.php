<?php

namespace Zeroem\UUID;

class UUID
{
  private 
    $time_low,
    $time_mid,
    $time_hi_and_version,
    $clock_seq_hi_and_reserved,
    $clock_seq_low,
    $node;

  static $subMicrosecondCount=0;
  static $sequence = null;

  public function __construct($node) {
    $timestamp = self::getUTCTimeStamp();
    $this->time_low = self::convert($timestamp & 0xffffffff);
    $this->time_mid = self::convert(($timestamp >> 32) & 0x000000ff);
    $this->time_hi_and_version = self::convert((($timestamp >> 48) & 0x0fffffff) | 0x10000000);

    $sequence = self::getSequence();
    $this->clock_seq_low = self::convert($sequence & 0xff);
    $this->clock_seq_hi_and_reserved = self::convert(($sequence >> 8) & 0x1f | 0x8);

    $this->node = $node;
  }

  public function __toString() {
    return sprintf(
      "%08s-%04s-%04s-%02s%02s-%012s",
      $this->time_low,
      $this->time_mid,
      $this->time_hi_and_version,
      $this->clock_seq_hi_and_reserved,
      $this->clock_seq_low,
      $this->node
    );
  }

  /**
   * Number of 100ns intervals since 1582-10-05
   */
  static function getUTCTimestamp() {
    list($ms,$sec) = explode(' ', microtime());
    $value = ((($sec - gmmktime(0,0,0,10,15,1582)) * 1000) + $ms + (self::$subMicrosecondCount)) * 10;
    self::$subMicrosecondCount = (self::$subMicrosecondCount+1) % 10;

    return $value;
  }

  static function convert($number) {
    return base_convert($number,10,16);
  }

  static private function getSequence() {
    if(!isset(self::$sequence)) {
      self::$sequence = mt_rand(0,0xffff);
    } else {
      self::$sequence++;
    }
    
    return self::$sequence;
  }
}
