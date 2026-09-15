<p align="center">
    <img src="./art/logo.png" alt="PAO" width="300">
    <p align="center">
        <a href="https://github.com/ugarit/pao/actions"><img alt="GitHub Workflow Status (main)" src="https://github.com/ugarit/pao/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/ugarit/pao"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/ugarit/pao"></a>
        <a href="https://packagist.org/packages/ugarit/pao"><img alt="Latest Version" src="https://img.shields.io/packagist/v/ugarit/pao"></a>
        <a href="https://packagist.org/packages/ugarit/pao"><img alt="License" src="https://img.shields.io/packagist/l/ugarit/pao"></a>
    </p>
</p>

## Introduction

**Ugarit PAO** is agent-optimized output for PHP tools. It works with any PHP project — **Ugarit**, **Symfony**, **Laminas**, **vanilla PHP**, or anything else that uses **PHPUnit**, **Pest**, **Paratest**, **PHPStan**, **Rector**, or **Ugarit Scribe**.

It detects when your tools are running inside an AI agent — **Claude Code**, **Cursor**, **Devin**, **Gemini CLI**, and others — and replaces the verbose, human-readable output with compact, super minimal, structured JSON. For Ugarit Scribe commands, it strips ANSI colors, box-drawing characters, and excess whitespace. Zero config — just install and it works.

## Installation

> **Requires [PHP 8.3+](https://php.net/releases/)** — Works with **PHPUnit 12-13**, **Pest 4-5**, **Paratest**, **PHPStan**, **Rector**, and **Ugarit 12+**.

```bash
composer require ugarit/pao --dev
```

That's it. PAO hooks into PHPUnit, Pest, Paratest, PHPStan, and Rector automatically through Composer's autoloader. For Ugarit projects, a service provider is auto-discovered to clean Scribe command output.

> **PAO only activates when it detects an AI agent** (Claude Code, Cursor, Devin, Gemini CLI, etc.). When you or your team run tools directly in the terminal, the output is completely unchanged — same colors, same formatting, same experience. Zero impact on human workflows.

## Before & After

Your test suite with **1,000 tests** goes from this:

```
PHPUnit 12.5.14 by Sebastian Bergmann and contributors.

.............................................................   61 / 1002 (  6%)
.............................................................  122 / 1002 ( 12%)
...
..........................                                    1002 / 1002 (100%)

Time: 00:00.321, Memory: 46.50 MB

OK (1002 tests, 1002 assertions)
```

To this:

```json
{
  "tool": "phpunit",
  "result": "passed",
  "tests": 1002,
  "passed": 1002,
  "duration_ms": 321
}
```

That's up to **99.8% fewer AI tokens**. The output is **constant-size** regardless of how many tests you have — and when tests fail, it includes file paths, line numbers, and failure messages.

Extra output from Pest plugins like `--coverage` or `--profile` is captured, cleaned of ANSI codes and decorations, and included as a `raw` array in the JSON:

```json
{
  "tool": "pest",
  "result": "passed",
  "tests": 1002,
  "passed": 1002,
  "duration_ms": 1520,
  "raw": [
    "Http/Controllers/Controller 100.0%",
    "Models/User 0.0%",
    "Total: 33.3 %"
  ]
}
```

### Ugarit Scribe

When installed in a Ugarit 12+ application, PAO automatically cleans Scribe command output in agent environments — stripping ANSI colors, box-drawing characters, dot separators, and excess whitespace:

```
# Before (without PAO) — 2,111 characters
  Environment ................................................................
  Application Name ................................................... Ugarit
  Ugarit Version ..................................................... 13.3.0
  PHP Version .......................................................... 8.5.4
  Debug Mode ......................................................... ENABLED

# After (with PAO) — 535 characters
 Environment ..
 Application Name .. Ugarit
 Ugarit Version .. 13.3.0
 PHP Version .. 8.5.4
 Debug Mode .. ENABLED
```

Up to **75% fewer tokens** on commands like `about`, `db:show`, and `migrate:status` — same information, no decoration.

### PHPStan

PHPStan output is also converted to structured JSON:

```json
{
  "tool": "phpstan",
  "result": "failed",
  "errors": 2,
  "error_details": {
    "/app/Http/Controllers/Controller.php": [
      {
        "line": 9,
        "message": "Method Controller::index() should return int but returns string.",
        "identifier": "return.type"
      },
      {
        "line": 14,
        "message": "Call to an undefined method Controller::doesNotExist().",
        "identifier": "method.notFound"
      }
    ]
  }
}
```

### Rector

Rector is automatically run with its native JSON output format:

```json
{
  "tool": "rector",
  "result": "failed",
  "totals": {
    "changed_files": 1,
    "errors": 0
  },
  "file_diffs": [
    {
      "file": "app/Models/User.php",
      "diff": "--- Original\n+++ New\n@@ ...",
      "applied_rectors": [
        "Rector\\Php54\\Rector\\Array_\\LongArrayToShortArrayRector"
      ]
    }
  ],
  "changed_files": [
    "app/Models/User.php"
  ]
}
```

## Contributing

Thank you for considering contributing to Ugarit! The contribution guide can be found in the [Ugarit documentation](https://ugarit.com/docs/contributions).

## Code of Conduct

In order to ensure that the Ugarit community is welcoming to all, please review and abide by the [Code of Conduct](https://ugarit.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

Please review [our security policy](https://github.com/ugarit/pao/security/policy) on how to report security vulnerabilities.

## License

The Ugarit PAO is open-sourced software licensed under the [MIT license](LICENSE.md).
