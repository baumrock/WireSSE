# WireSSE

## Setup

Until SSE is merged into core, we need to include it manually in `/site/init.php`:

```php
require_once __DIR__ . '/modules/WireSSE/WireSSE.php';
wire()->wire('sse', $this->wire(new WireSSE()));
```

## Usage

Usually you need two things for SSE:

1. An endpoint that sends the data
2. A frontend that receives the data

### Endpoint (Backend)

```php
// /site/init.php
wire()->addHookAfter('/sse-clock', function () {
  wire()->sse->loop(function (WireSSE $sse) {
    $sse->send(date('Y-m-d H:i:s'));
    sleep(1);
  });
});
```

Now visit `/sse-clock` in your browser and you should see something like this:

```
data: 2024-09-16 19:13:55
data: 2024-09-16 19:13:56
data: 2024-09-16 19:13:57
data: 2024-09-16 19:13:58
data: 2024-09-16 19:13:59
data: 2024-09-16 19:14:00
data: 2024-09-16 19:14:01
```

... updating every second.

### Client (Frontend)

Having only the stream of data is not very useful. Usually you want to do something with the data on the frontend.

For this we can use Server-Sent Events (SSE) in JavaScript:

```js
// any js file that is loaded in your frontend
// eg /site/templates/js/main.js
const sse = new EventSource('/sse-clock');
sse.onmessage = function(event) {
  console.log(event.data);
};
```

Now you should see the time in the console every second.
