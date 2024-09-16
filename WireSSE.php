<?php

namespace ProcessWire;

class WireSSE extends Wire
{
  /**
   * Add an SSE endpoint
   *
   * This will attach a hook to $url and set appropriate headers for the SSE
   * stream. The callback will be called in an endless loop and can be used to
   * run tasks and send messages to the client.
   */
  public function addEnpoint(string $url, callable $callback): void
  {
    wire()->addHookAfter($url, function (HookEvent $event) use ($callback) {
      // we dont want warnings in the stream
      // for debugging you can uncomment this line
      error_reporting(E_ALL & ~E_WARNING);
      header("Cache-Control: no-cache");
      header("Content-Type: text/event-stream");
      while (true) $callback($this, $event);
    });
  }

  /**
   * Send SSE message to client
   */
  public function send(mixed $msg): void
  {
    if (!is_string($msg)) $msg = json_encode($msg);
    echo "data: $msg\n\n";
    echo str_pad('', 8186) . "\n";
    flush();
  }
}
