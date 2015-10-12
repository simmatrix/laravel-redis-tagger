# chalcedonyt/laravel-redis-tagger

A helper Facade and functions to organize Redis tags, enabling easy type-hinting, built-in parameter validation, and placeholder replacement.

## Install

Via Composer (minimum stability must be set to dev)

``` bash
$ composer require chalcedonyt/laravel-redis-tagger
```

Include the Provider and Facade into app.php. Right now this package only supports Key-Value, Sets will be supported in the future:

```
Chalcedonyt\RedisTagger\Providers\RedisTaggerServiceProvider::class
```
```
'RedisKVTagger' => Chalcedonyt\RedisTagger\Facades\RedisKVTagger::class
```
## Usage

``` php
php artisan redis_tagger:make:keyvalue UserPosts\\PostCount
```

The only thing you need to set is the `signatureKeys` value. You may insert either a plain string, a {signature}, or a {placeholder} with a \Closure that returns a value. (This allows type-hinting).

Any {signature} keys must be defined when called.

```
class PostCount extends KeyValue
{
    public function __construct(){
        parent::__construct();
        $this -> signatureKeys = [
            'user_posts',
            '{type}',
            '{post_id}' => function( Post $post ){
                return $post -> id;
            },
            'count'
        ];
    }
}
```

You may then call ::set or ::get on the `RedisKVTagger` Facade:

```
$post = new Post();
$post -> id = 123
$args = ['type' => 'article', 'post_id' => $post ];
RedisKVTagger::set('UserPosts\PostCount', $args, 1000); //sets the key "user_posts:article:123:count" to 1000.
```

Likewise, you can retrieve the value with
```
RedisKVTagger::get('UserPosts\PostCount', $args);
```

It is also possible to extend any KeyValue taggers you create by adding to the parent's $signatureKeys variable.

```
class PostCountToday extends PostCount
{
    public function __construct(){
        parent::__construct();
        $this -> signatureKeys[]= 'today';
    }    
}
```
```
class PostCountYesterday extends PostCount
{
    public function __construct(){
        parent::__construct();
        $this -> signatureKeys[]= 'yesterday';
    }    
}
```

```
RedisKVTagger::set('UserPosts\PostCountToday', $args, 1000); //sets the key "user_posts:article:123:count:today" to 1000.
RedisKVTagger::set('UserPosts\PostCountYesterday', $args, 1000); //sets the key "user_posts:article:123:count:yesterday" to 1000.
```
## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.



## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.