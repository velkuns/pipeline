# math

[![Current version](https://img.shields.io/packagist/v/velkuns/math.svg?logo=composer)](https://packagist.org/packages/velkuns/math)
[![Supported PHP version](https://img.shields.io/static/v1?logo=php&label=PHP&message=8.2%20-%208.3&color=777bb4)](https://packagist.org/packages/velkuns/math)
![CI](https://github.com/velkuns/math/workflows/CI/badge.svg)

## Why?

Some common code for Puzzle game like Codingame or Advent Of Code

## Installation

If you wish to install it in your project, require it via composer:

```bash
composer require velkuns/math
```


## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.


### Install / update project

You can install project with the following command:
```bash
make install
```

And update with the following command:
```bash
make update
```

NB: For the components, the `composer.lock` file is not committed.

### Testing & CI (Continuous Integration)

#### Tests
You can run tests (with coverage) on your side with following command:
```bash
make tests
```

For prettier output (but without coverage), you can use the following command:
```bash
make testdox # run tests without coverage reports but with prettified output
```

#### Code Style
You also can run code style check with following commands:
```bash
make phpcs
```

You also can run code style fixes with following commands:
```bash
make phpcbf
```

#### Static Analysis
To perform a static analyze of your code (with phpstan, lvl 9 at default), you can use the following command:
```bash
make analyze
```

Minimal supported version:
```bash
make php82compatibility
```

Maximal supported version:
```bash
make php83compatibility
```

#### CI Simulation
And the last "helper" commands, you can run before commit and push, is:
```bash
make ci  
```


## License

This project is licensed under the MIT License - see the `LICENSE` file for details
