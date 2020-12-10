# Progressive

Progressive is a feature flag library *(also called toggle, switch, ...)*.  
Thanks to Progressive, you can **progressively**, **quickly** and **simply** enable new features.
(and just as quickly deactivate them in case of an emergency)

[![Build Status](https://travis-ci.org/antfroger/progressive.svg?branch=master)](https://travis-ci.org/antfroger/progressive)
[![Latest Stable Version](https://poser.pugx.org/antfroger/progressive/v/stable.png)](https://packagist.org/packages/antfroger/progressive "Latest Stable Version")

## Installation

```bash
    composer require antfroger/progressive
```

## Usage

```php
$config      = [
    'features' => [
        'dark-theme'  => true,
        'call-center' => false,
    ]
];
$progressive = new Progressive($config);
$progressive->isEnabled('dark-theme');    // true
$progressive->isEnabled('call-center');   // false
```

## Rules

### Built-in

#### `enabled: true|false`

`enabled` enables (or disables) the feature for everyone, everywhere, all the time.  
The value is meant to be a boolean, `true|false`.

```php
// Short
$config      = [
    'features' => [
        'dark-theme' => true
    ]
];

// Verbose
$config = [
    'features' => [
        'dark-theme'  => [
            'enabled' => true
        ]
    ]
];
```

### Custom

You will probably need many more rules that will fit your needs and stack.

Let's say you want to redesign your homepage and progressively displaying it to test if everything goes right.  
You start by enabling it in dev, then preprod, then prod but only for developers, then admins, then 1% of the users...  
How would you be able to achieve that?

With custom rules!

```php
$config = [
    'features' => [
        'homepage-v123'  => [
            'env' => ['DEV', 'PREPROD']
        ]
    ]
];

$progressive = new Progressive($config);
$progressive->addCustomRule(
    'env',
    function(Context $context, array $envs) {
        return in_array(getenv('ENV'), $envs);
    }
);

$progressive->isEnabled('homepage-v123'); // Returns true if getenv('ENV') is DEV or PREPROD, otherwise returns false
```

(this lambda can be improved thanks to the `Context object` - more about it [here](#context-object))

## Strategies

Rules are great but sometimes one rule id not enough to decide what to do.  
You may want to enable a feature to the admins **AND** a given percentage of users.  
Or you may want to enable a feature in `PROD` only for admins but to everyone in `PREPROD`.

That's where strategies come into play!

### `unanimous: []`

`unanimous` enables the feature if **all** the conditions are met.  
The value is meant to be an array of [rules](#rules).

```php
$config = [
    'features' => [
        'translate-interface'  => [
            'unanimous' => [
                'env'   => ['DEV', 'PREPROD'],
                'roles' => ['ROLE_ADMIN', 'ROLE_TRANSLATOR']
            ]
        ]
    ]
];
```

In this example, the interface to translate the app will only be displayed in `DEV` and `PREPROD` environments to users having `ROLE_ADMIN` or `ROLE_TRANSLATOR` as roles.  
This strategy can be defined as an **AND**.

### `partial: []`

`partial` enables the feature if only **one** of the conditions is met.  
Rules are evaluated one by one. The feature is enables as soon as one rule is true.  
The value is meant to be an array of [rules](#rules).

```php
$config = [
    'features' => [
        'translate-interface'  => [
            'partial' => [
                'env'   => ['DEV', 'PREPROD'],
                'roles' => ['ROLE_ADMIN', 'ROLE_TRANSLATOR']
            ]
        ]
    ]
];
```

In this example, the interface to translate the app will be displayed:

* in `DEV` and `PREPROD` environments (for all users)
* for users having a role `ROLE_ADMIN` or `ROLE_TRANSLATOR`, regardless of the environment.

This strategy can be defined as an **OR**.

## Context object

Progressive doesn't know anything of your application logic code (and doesn't want to know).  
But you may want to use your logic inside custom rules.

In that case, the context object is your friend!  
It's nothing more that an user-defined databag.

You can use to improve our previous example of custom rule:

```php
$context = (new Context())->add('env', getenv('ENV') ?: 'PROD');
$config = [
    'features' => [
        'homepage-v123'  => [
            'env' => ['DEV', 'PREPROD']
        ]
    ]
];

$progressive = new Progressive($config, $context);
$progressive->addCustomRule(
    'env',
    function(Context $context, array $envs) {
        return in_array($context->get('env'), $envs);
    }
);
```

Another common example is to store the current user in the context:

```php
$context = (new Context())->add('user', $user);
$config = [
    'features' => [
        'homepage-v123'  => [
            'roles' => ['ROLE_ADMIN']
        ]
    ]
];

$progressive = new Progressive($config, $context);
$progressive->addCustomRule(
    'roles',
    function(Context $context, array $roles) {
        $userRoles = $context->get('user')->getRoles();
        foreach ($roles as $role) {
            if (in_array($role, $userRoles) {
                return true;
            }
        }

        return false;
    }
);
```

## Requesting a non-existing flag

Whenever you request an non-existing flag, `isEnabled` will return false.  
The flag is considered disabled.

```php
$config = [
    'features' => []
];

$progressive = new Progressive($config);
$progressive->isEnabled('dark-theme'); // false
```

## Separate feature releases from code deploy

As Progressive takes an array of config, it makes it possible to decorrelate the release calendar from the code deployment.  
In this example, we use the Symfony Yaml component to read the config from a YAML file.

```php
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('/path/to/your/config/file.yaml');
$progressive = new Progressive($config);
$progressive->isEnabled('new-feature'); // false
```

Imagine that you are able to deploy the config files independently from your code base, you could release a new feature withtout having to deploy all your code base, by redeploying only `/path/to/your/config/file.yaml`.

```php
$progressive->isEnabled('new-feature'); // true
```

You may also want to store the configuration in a database and pass it as an array to Progressive.

---

*Inspired by [Laurent Callarec](https://github.com/lcallarec)'s javascript feature-flag library [Banderole](https://github.com/lcallarec/banderole)*
