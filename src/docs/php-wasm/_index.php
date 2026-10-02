<?php
/**
 * @title    PHP-WASM runtime
 * @section  docs
 * @type     docs
 * @group    Extend
 * @position 23
 * @abstract PHP 8.5 compiled to WebAssembly for Node: how to run it directly,
 *           its networking, its extensions and its limits.
 */
?>

<markdown>
  ## Overview

  `@kirigami/php-wasm` is what lets Kirigami run PHP without PHP installed.
  It ships a PHP **8.5.11** WebAssembly binary built by Kirigami's own
  compiler, [`php-wasm-compiler`](https://github.com/php-kirigami/php-wasm-compiler),
  with a Node.js loader and a few runtime helpers. You never need it to build
  a site: the [engine](../api/) and [PHP-prepros](../php/) use it for you.
  Use it directly to run PHP from your own Node code.

  It is deliberately narrow:

  - **JSPI** (JavaScript Promise Integration) target only, and **Node.js**
    only. No browser build, no worker or iframe targets, no Asyncify.
  - Needs Node **24 or later**. Check `await jspi()` before creating a runtime,
    since an embedded editor runtime may not support it.

  ```bash
  npm install @kirigami/php-wasm
  ```

  The version of the package is the version of PHP it contains: `8.5.11` is
  PHP 8.5.11. The package is licensed **GPL-2.0-or-later**, separately from
  the GPL-3.0 core, since it carries a compiled PHP runtime.

  ## Running PHP

  ### A quick snippet with `exec()`

  `exec()` writes your code to a temporary file, runs it, cleans up and hands
  back a plain object. The opening PHP tag is added for you.

  ```js
  import { exec, phpversion } from '@kirigami/php-wasm';

  const { returnCode, stdout, stderr } = await exec('echo "Hello, Kirigami!";');
  console.log(returnCode, stdout, stderr);   // 0 "Hello, Kirigami!" ""

  console.log(await phpversion());           // "8.5.11"
  ```

  Pass `true` as the second argument to run against the network-enabled
  runtime. `phpinfo()` returns the HTML of PHP's own `phpinfo()`.

  ### An instance you keep

  To write several files into the virtual filesystem and run them in turn,
  take the instance itself:

  ```js
  import { readFile } from 'node:fs/promises';
  import { getPHPRuntime } from '@kirigami/php-wasm';

  const php = await getPHPRuntime();

  // hello.php is a normal PHP file on your disk, opening tag included.
  php.writeFile('/hello.php', await readFile('hello.php'));

  const response = await php.runStream({ scriptPath: '/hello.php' });
  console.log(await response.stdoutText);
  ```

  Unlike `exec()`, a file written this way must start with PHP's opening tag
  itself.

  `getPHPRuntime()` and `getPHPRuntimeWithNetwork()` are **memoized**: every
  call in the same process returns the same shared instance, so its state
  persists. To get an independent one, use `createPHPRuntime()` and call
  `php.exit()` when you are done (it also closes the network proxy and its
  sockets).

  ### Low level

  For manual construction, load the module and hand its id to the `PHP` class
  of [`@php-wasm/universal`](https://www.npmjs.com/package/@php-wasm/universal),
  which still provides `PHP` and `loadPHPRuntime()`:

  ```js
  import { getPHPLoaderModule } from '@kirigami/php-wasm';
  import { PHP, loadPHPRuntime } from '@php-wasm/universal';

  const php = new PHP(await loadPHPRuntime(await getPHPLoaderModule()));
  ```

  ## The helpers

  | Export | What it does |
  |---|---|
  | `getPHPLoaderModule()` | The raw JSPI PHP 8.5 loader module. |
  | `jspi()` | Detects JSPI support in the current runtime. |
  | `getPHPRuntime()` | A standard PHP instance (memoized singleton). |
  | `getPHPRuntimeWithNetwork()` | An instance bound to a local outbound proxy, with Node's SSL root certificates injected (a separate memoized singleton). |
  | `createPHPRuntime({ network? })` | An independent, owned runtime; neither singleton is touched. |
  | `getLoadedExtensions()` | The names of every loaded extension, sorted without regard to case. |
  | `exec(code, network?)` | Run a snippet; returns `{ returnCode, stdout, stderr }`. |
  | `phpversion()`, `phpinfo()` | The version string, and the `phpinfo()` HTML. |
  | `setPhpIniValues(php, values, iniPath?)` | Update or add `php.ini` directives. Also `php.setIniValues(values)` on the two memoized instances. |
  | `getPhpIniValue(php, key, iniPath?)` | Read one active (uncommented) directive. |

  The instances are typed: `getPHPRuntime()` resolves to a `KirigamiPHP`
  (a `PHP` with `.setIniValues()`), and `getPHPRuntimeWithNetwork()` to a
  `KirigamiNetworkPHP`, which adds `._networkProxyServer`.

  ## Networking

  PHP inside WebAssembly has no sockets of its own. The network runtime bridges
  them: a local proxy built on `node:http`, `node:net` and `node:dgram` turns the
  runtime's socket calls into real outbound **TCP** connections, and **UDP** for
  datagram sockets. Node's root certificates are handed to the PHP side, so
  **cURL and OpenSSL HTTPS requests work immediately**.

  ```js
  import { exec, jspi } from '@kirigami/php-wasm';

  if (!(await jspi())) throw new Error('WASM JSPI is not available here.');

  const { stdout } = await exec(`
      $ch = curl_init("https://api.github.com/zen");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERAGENT, "Kirigami-PHP-WASM");
      echo curl_exec($ch);
  `, true);
  console.log(stdout);
  ```

  Details for the curious:

  - UDP works through PHP streams (`stream_socket_client('udp://host:port')`,
    `fsockopen`) and through the `sockets` extension (`socket_sendto()`,
    `socket_recvfrom()`, `socket_select()`).
  - Blocking reads wait for data, end of file or `SO_RCVTIMEO`. `connect()`
    waits for the proxy and fails with `ECONNREFUSED` when it can't reach the
    destination. `TCP_NODELAY` and `SO_KEEPALIVE` are applied to the real
    connection; other options fail with `ENOPROTOOPT`.
  - libcurl waits in `poll()`, which would freeze Node: the runtime replaces it
    with a version that yields to the event loop.
  - The proxy only listens on `127.0.0.1`. Closing it is final for that cached
    instance: it is not a per-request cleanup.

  ## Extensions built in

  These are baked into the binary, so they are always loaded. A single
  `config.yaml` in `php-wasm-compiler` is the source of truth for the list.

  | Extension | Purpose |
  |---|---|
  | `curl`, `openssl` | HTTP(S) client, TLS and crypto |
  | `libxml`, `dom`, `simplexml`, `xmlreader`, `xmlwriter` | XML and DOM |
  | `mbstring`, `iconv` | Multibyte strings (with oniguruma) and character sets |
  | `gd`, `imagick`, `exif` | Image processing, ImageMagick and metadata |
  | `sockets` | Low-level sockets |
  | `zip`, `bz2` | ZIP archives; BZip2, which also gives `Phar` its `.tar.bz2` support |
  | `sqlite3`, `pdo`, `pdo_sqlite` | SQLite and PDO |
  | `opcache` | Bytecode cache (JIT disabled) |
  | `yaml` | YAML 1.1 through LibYAML |
  | `jsonpath` | JSONPath queries over decoded JSON |
  | `fastcsv` | Streaming CSV reading and writing (`FastCSVReader`, `FastCSVWriter`, `FastCSVConfig`) |
  | `aspect` | The `Memoize` class, for caching function results |
  | `apcu`, `igbinary` | In-memory user cache and compact serialization |

  Four more are Kirigami's own, vendored from their repositories:

  - [`jsonk`](https://github.com/php-kirigami/php-jsonk): fast JSON encode and
    decode plus **JSON Schema** validation; it replaces `json_encode()` and
    `json_decode()` by default.
  - [`mdhtml`](https://github.com/php-kirigami/php-mdhtml): CommonMark and GFM
    through `cmark-gfm`, the backend of the `MD::` class.
  - [`navicat`](https://github.com/php-kirigami/php-navicat): a client for
    Navicat Premium's HTTP-tunnel protocol, for MySQL, PostgreSQL and SQLite.
  - [`norm`](https://github.com/php-kirigami/php-norm): Unicode normalization
    (`Normalizer`) over `utf8proc`.

  `lexbor` (HTML5 parsing and CSS selectors) is built in as well. Don't assume
  an extension from another PHP build: ask `getLoadedExtensions()`, or run
  `kiri phpinfo -m` (Markdown) or `-j` (JSON) from a project.

  > The PHP **YAML** path follows YAML 1.1 scalar rules, including implicit
  > booleans: quote a string such as `"NO"`. Node's own parsing of
  > `kirigami.yaml` is a separate path with different rules.

  ## More extensions, on demand

  Everything else ships as separate WebAssembly side modules in
  `@kirigami/phpext-*` packages. Install one and the runtime picks it up on its
  own, with no rebuild:

  ```bash
  npm install @kirigami/phpext-pgsql
  ```

  Published today: `anydoc`, `dba`, `enchant`, `fastchart`, `ffi`, `fileinfo`,
  `ftp`, `gettext`, `gmp`, `intl`, `ldap`, `mysqli`, `odbc`, `pdo_dblib`,
  `pdo_firebird`, `pdo_mysql`, `pdo_odbc`, `pdo_pgsql`, `pgsql`, `posix`, `rar`,
  `scanmeqr`, `snmp`, `soap`, `sodium`, `tidy` and `xsl`. `mysqli` and
  `pdo_mysql` bundle `mysqlnd`. Two worth a warning: `intl` is about 38 MB since
  it embeds the ICU data, and `anydoc` is written in Rust, where a panic aborts
  the whole PHP runtime.

  ### How discovery works

  When a runtime is created, the loader looks for directories named `phpext-*`
  (also under `@kirigami`) in `node_modules` and `packages`, in the working
  directory and its ancestors, and in npm's global root. It does not search
  sibling projects, so an unrelated compiler checkout next to your site can't
  change its runtime.

  - A package whose `index.js` exports a default `register(phpVersion)` (all
    the generated ones do) returns its modules in load order. One package can
    bundle a dependency ahead of its extension: `mysqli` ships `mysqlnd` first.
  - Otherwise a `manifest.json` may list artifacts per PHP version, and failing
    that the first `.so` found is used.
  - The resolved modules are staged under `/internal/shared/extensions`, each
    with a numbered `.ini` file that keeps the discovery order.
  - Set `KIRIGAMI_PHPEXT_DISCOVERY` to `local` to skip the global root, or `off`
    to disable discovery.

  Finding a `.so` does not prove PHP accepted it, and artifact selection does
  not establish binary compatibility: check `getLoadedExtensions()` for what
  actually loaded. Cached runtimes are not rescanned on every execution.

  ## Security

  Code run through `exec()` or a `PHP` instance lives in the WebAssembly
  sandbox: it sees a **virtual** filesystem (`writeFile()` and `unlink()` don't
  touch your disk) unless the host mounts files or installs bridges. PHP-prepros
  adds such integration, so don't treat a project's PHP as untrusted input.

  - **The network runtime opens the door to the network.** The proxy only
    listens locally, but the PHP code inside can reach out over TCP and UDP
    like any client.
  - WebAssembly reduces host exposure but is **not a security boundary** for
    fully untrusted PHP, such as code submitted by users. Add a container or a
    virtual machine for that.
  - It is not `child_process.exec()`, which runs directly on the host.

  ## Where it comes from

  The binary (`jspi/8_5_11/php_8_5.wasm`) and its Emscripten loader are built
  by [`php-wasm-compiler`](https://github.com/php-kirigami/php-wasm-compiler)
  with Docker and Emscripten, from a single `config.yaml` that sets the PHP
  version, the extensions, the libraries and the build options. The same
  compiler publishes the `@kirigami/phpext-*` packages. Its recipes began from
  WordPress Playground's compile pipeline; see its `NOTICE.md` for provenance.
  The JavaScript side of this package (the networking proxy, extension
  discovery, `php.ini` helpers) is Kirigami code.
</markdown>
