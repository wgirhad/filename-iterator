# filename-iterator
Glob-like recursive file iterator

![Lint](https://github.com/wgirhad/filename-iterator/actions/workflows/lint.yml/badge.svg)
![Test](https://github.com/wgirhad/filename-iterator/actions/workflows/test.yml/badge.svg)
![PHPStan](https://github.com/wgirhad/filename-iterator/actions/workflows/phpstan.yml/badge.svg)

## Usage Examples

Given the following file structure:

```
src/CoolestClass.php
src/SecondCoolest.php
tests/CoolestClassTest.php
tests/SecondCoolestTest.php
public/static/foo.png
public/static/bar.gif
public/static/images/bar.png
public/static/images/baz.png
public/static/images/fizz.jpeg
public/static/images/buzz.jpg
public/static/images/.peek/aboo.png
```

Listing all `.png` images on `public/static`
```php
<?php

use Wgirhad\FilenameIterator\Iterator as FilenameIterator;

foreach (new FilenameIterator('public/static', '*.png') as $filename) {
    echo "{$filename}\n";
}

// outputs:
// public/static/foo.png
// public/static/images/bar.png
// public/static/images/baz.png
// public/static/images/.peek/aboo.png

```

Listing all `Coolest` files
```php
<?php

use Wgirhad\FilenameIterator\Iterator as FilenameIterator;

foreach (new FilenameIterator('*', '*Coolest*.php') as $filename) {
    echo "{$filename}\n";
}

// outputs:
// src/CoolestClass.php
// src/SecondCoolest.php
// tests/CoolestClassTest.php
// tests/SecondCoolestTest.php

```
