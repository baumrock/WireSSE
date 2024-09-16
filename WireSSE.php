<?php

namespace ProcessWire;

class WireSSE extends Wire
{
  /**
   * Context that can be set in the init callback and can
   * be consumed by the loop callback.
   * @var WireData
   */
  public $context;

  public function __construct()
  {
    $this->context = new WireData();
  }

  public function loop(
    callable $callback,
    callable $init = null,
  ): void {
    // we dont want warnings in the stream
    // for debugging you can uncomment this line
    error_reporting(E_ALL & ~E_WARNING);

    // set headers
    header("Cache-Control: no-cache");
    header("Content-Type: text/event-stream");

    // if init callback is set, call it
    if ($init) $init($this);

    // start endless loop and call callback
    while (true) {
      // get the result of the callback
      $result = $callback($this);

      // if result is FALSE, we break the loop
      if ($result === false) break;

      // if connection is aborted, we break the loop
      if (connection_aborted()) break;
    }
  }

  /**
   * Send SSE message to client
   */
  public function send(mixed $msg = ''): void
  {
    if (!is_string($msg)) $msg = json_encode($msg);
    echo "data: $msg\n\n";
    echo str_pad('', 8186) . "\n";
    flush();
  }
}
