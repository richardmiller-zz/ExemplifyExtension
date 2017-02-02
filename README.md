ExemplifyExtension
==================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/richardmiller/ExemplifyExtension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/richardmiller/ExemplifyExtension/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/richardmiller/ExemplifyExtension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/richardmiller/ExemplifyExtension/build-status/master)

PhpSpec extension that adds exemplify command to generate examples in specs.

For example, running:

```
bin/phpspec exemplify RMiller/Badger dig
```

And choosing the default option of 'instance method', will add the following example
to the spec/RMiller/BadgerSpec class:

```
public function it_should_dig()
{
    $this->dig();
}
```

This can then be modified to describe the behaviour for the method.

## Installation

Requires:

* PhpSpec 3.0+
* PHP 5.6+

To use 'named constructor' examples , you need to use phpspec `>2.1`.
Otherwise the examples will be created but will not run.

Require the extension:

```
$ composer require --dev rmiller/exemplify-extension:^0.5
```

## Configuration

Activate the extension by specifying its class in your `phpspec.yml`:

```yaml
# phpspec.yml
extensions:
    RMiller\BehatSpec\Extension\ExemplifyExtension\ExemplifyExtension: ~
```

## Method Types

Three different method types are supported, on running the command you will be
asked which type of method is being described. These are:

* Instance Method (e.g. $this->dig())
* Static Method (e.g. $this::dig())
* Named Constructor

The names constructor option is for static methods used to instantiate and return
an instance of the class. It is essentially another name for a factory method. This
is listed separately as the example created is different.

For example, running:

```
bin/phpspec exemplify RMiller/Badger withName
```

And choosing the option of 'named constructor', will add the following
to the spec/RMiller/BadgerSpec class:

```
public function it_should_be_constructed_through_with_name()
{
    $this->beConstructedThrough('withName');
}
```

## Other Potentially Useful Extensions

* For further laziness [PhpSpecRunExtension](https://github.com/richardmiller/PhpSpecRunExtension)
will execute the phpspec run command after the describe and exemplify commands,
saving a few keystrokes.

* This extension and PhpSpecRun are also part of [BehatSpec](https://github.com/richardmiller/BehatSpec)
which provides integration between Behat and PhpSpec. This includes running the exemplify
command automatically for missing methods encountered when running Behat features.




