# WireSSE

## Setup

Until SSE is merged into core, we need to include it manually in `/site/init.php`:

```php
require_once __DIR__ . '/modules/WireSSE/WireSSE.php';
wire('sse', $this->wire(new WireSSE()));
```

## Usage

To use SSE you need to add an endpoint from the backend:

```php
// /site/init.php
wire()->sse->addEnpoint(
  // the url path where you want to access the endpoint
  '/sse-clock',

  // the callback function that is executed in an endless loop
  // by default it will sleep for 1 second between each call
  function($sse, $event) {
    $sse->send(date('Y-m-d H:i:s'));
    sleep(1);
  },

  // optional init function that is called once when the endpoint is setup
  // function($sse, $event) {
  //   wire()->config->foo = 'foo';
  // },
);
```

And then you can start a connection from the frontend:

```js
// any js file that is loaded in your frontend
// eg /site/templates/js/main.js
const sse = new EventSource('/sse-clock');
sse.onmessage = function(event) {
  console.log(event.data);
};
```
